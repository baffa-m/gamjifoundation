<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\{Application, Applicant, Award};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        if (!$applicant) {
            return redirect()->route('applicant.profile.create')
                ->with('message', 'Please complete your profile to continue.');
        }
        
        $query = Application::where('applicant_id', $applicant->id)
            ->with('award');

        if ($request->status) {
            $query->where('application_status', $request->status);
        }

        return Inertia::render('Applicant/Applications/Index', [
            'applications' => $query->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only(['status'])
        ]);
    }

    public function show(Application $application)
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        if (!$applicant) {
            return redirect()->route('applicant.profile.create')
                ->with('message', 'Please complete your profile to continue.');
        }
        
        // Ensure user can only view their own application
        if ($application->applicant_id !== $applicant->id) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('Applicant/Applications/Show', [
            'application' => $application->load('award.sponsor')
        ]);
    }

    public function store(Request $request, Award $award)
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        if (!$applicant) {
            return redirect()->route('applicant.profile.create')
                ->with('message', 'Please complete your profile to continue.');
        }

        // Check if award is still open
        if (!$award->isOpenForApplications()) {
            return back()->with('error', 'This award is no longer accepting applications.');
        }

        // Check if already applied
        if (Application::where('applicant_id', $applicant->id)
            ->where('award_id', $award->id)
            ->exists()) {
            return back()->with('error', 'You have already applied for this award.');
        }

        // Handle Documents Upload
        if ($request->hasFile('jamb_file')) {
            $path = $request->file('jamb_file')->store('applicants/documents', 'public');
            $applicant->documents()->updateOrCreate(
                ['type' => 'jamb'],
                ['file_path' => $path, 'score' => $request->jamb_score]
            );
        }

        if ($request->hasFile('waec_file')) {
            $path = $request->file('waec_file')->store('applicants/documents', 'public');
            $applicant->documents()->updateOrCreate(
                ['type' => 'waec'],
                ['file_path' => $path]
            );
        }
        
        // Enforce Eligibility
        $category = strtolower($award->category);
        if ($category === 'jamb') {
            $jambDoc = $applicant->getJambDocument();
            if (!$jambDoc) {
                 return back()->with('error', 'JAMB result upload is required for this award.');
            }
            // Optional: Check score strictness if needed, but for now just presence
        }

        if ($category === 'waec') {
            $waecDoc = $applicant->getWaecDocument();
             if (!$waecDoc) {
                 return back()->with('error', 'WAEC result upload is required for this award.');
            }
        }

        Application::create([
            'applicant_id' => $applicant->id,
            'award_id' => $award->id,
            'application_number' => 'APP-' . strtoupper(Str::random(10)),
            'application_date' => now(),
            'application_status' => 'pending',
            // supporting_documents logic can be deprecated or used for supplementals
            // jamb_score column in application table also becomes redundant or a snapshot
            'jamb_score' => $applicant->getJambDocument()?->score, 
        ]);

        return redirect()->route('applicant.applications.index')
            ->with('success', 'Application submitted successfully!');
    }
}
