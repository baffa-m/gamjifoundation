<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\{Award, Applicant, Application};
use Illuminate\Http\Request;
use Inertia\Inertia;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        if (!$applicant) {
            return redirect()->route('applicant.profile.create')
                ->with('message', 'Please complete your profile to continue.');
        }
        
        $query = Award::active()
            ->where('application_end_date', '>=', now())
            ->with('sponsor.user')
            ->withCount('applications');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // Get IDs of awards user has already applied to
        $appliedAwardIds = Application::where('applicant_id', $applicant?->id)
            ->pluck('award_id')
            ->toArray();

        return Inertia::render('Applicant/Awards/Index', [
            'awards' => $query->latest()->paginate(12)->withQueryString(),
            'filters' => $request->only(['category', 'search']),
            'appliedAwardIds' => $appliedAwardIds
        ]);
    }

    public function show(Award $award)
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        if (!$applicant) {
            return redirect()->route('applicant.profile.create')
                ->with('message', 'Please complete your profile to continue.');
        }
        
        $hasApplied = Application::where('applicant_id', $applicant?->id)
            ->where('award_id', $award->id)
            ->exists();

        return Inertia::render('Applicant/Awards/Show', [
            'award' => $award->load('sponsor.user'),
            'hasApplied' => $hasApplied,
            'canApply' => $award->isOpenForApplications() && !$hasApplied,
            'applicant' => $applicant->load('documents'),
        ]);
    }
}