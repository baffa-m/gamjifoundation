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

        $validated = $request->validate([
            'supporting_documents' => 'nullable|string',
            'jamb_score' => 'nullable|integer|min:0|max:400',
        ]);

        Application::create([
            'applicant_id' => $applicant->id,
            'award_id' => $award->id,
            'application_number' => 'APP-' . strtoupper(Str::random(10)),
            'application_date' => now(),
            'application_status' => 'pending',
            'supporting_documents' => $validated['supporting_documents'] ?? null,
            'jamb_score' => $validated['jamb_score'] ?? null,
        ]);

        return redirect()->route('applicant.applications.index')
            ->with('success', 'Application submitted successfully!');
    }
}
