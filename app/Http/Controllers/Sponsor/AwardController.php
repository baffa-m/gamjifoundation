<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\{Award, Sponsor};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();
        
        $query = Award::where('sponsor_id', $sponsor->id)
            ->withCount('applications');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return Inertia::render('Sponsor/Awards/Index', [
            'awards' => $query->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only(['status']),
            'sponsor' => $sponsor
        ]);
    }

    public function create()
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();

        if ($sponsor->verification_status !== 'verified') {
            return redirect()->route('sponsor.dashboard')
                ->with('error', 'Your sponsor account must be verified before creating awards.');
        }

        return Inertia::render('Sponsor/Awards/Create');
    }

    public function store(Request $request)
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:jamb,waec,general,stem,arts,sports',
            'application_start_date' => 'required|date|after:today',
            'application_end_date' => 'required|date|after:application_start_date',
            'announcement_date' => 'nullable|date|after:application_end_date',
            'number_of_awards' => 'required|integer|min:1',
            'eligibility_criteria' => 'nullable|array',
            'required_documents' => 'nullable|string',
            'award_image' => 'nullable|image|max:2048'
        ]);

        $validated['sponsor_id'] = $sponsor->id;
        $validated['status'] = 'draft'; // Needs admin approval

        if ($request->hasFile('award_image')) {
            $validated['award_image'] = $request->file('award_image')->store('awards', 'public');
        }

        Award::create($validated);

        return redirect()->route('sponsor.awards.index')
            ->with('success', 'Award created and pending admin approval!');
    }

    public function show(Award $award)
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();
        
        // Ensure sponsor can only view their own awards
        if ($award->sponsor_id !== $sponsor->id) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('Sponsor/Awards/Show', [
            'award' => $award->load(['applications' => function($q) {
                $q->with('applicant.user');
            }])
        ]);
    }

    public function edit(Award $award)
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();
        
        if ($award->sponsor_id !== $sponsor->id) {
            abort(403, 'Unauthorized');
        }

        // Can only edit draft awards
        if ($award->status !== 'draft') {
            return redirect()->route('sponsor.awards.show', $award)
                ->with('error', 'Only draft awards can be edited.');
        }

        return Inertia::render('Sponsor/Awards/Edit', [
            'award' => $award
        ]);
    }

    public function update(Request $request, Award $award)
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();
        
        if ($award->sponsor_id !== $sponsor->id) {
            abort(403, 'Unauthorized');
        }

        if ($award->status !== 'draft') {
            return back()->with('error', 'Only draft awards can be edited.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:jamb,waec,general,stem,arts,sports',
            'application_start_date' => 'required|date',
            'application_end_date' => 'required|date|after:application_start_date',
            'announcement_date' => 'nullable|date|after:application_end_date',
            'number_of_awards' => 'required|integer|min:1',
            'eligibility_criteria' => 'nullable|array',
            'required_documents' => 'nullable|string',
            'award_image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('award_image')) {
            if ($award->award_image) {
                Storage::disk('public')->delete($award->award_image);
            }
            $validated['award_image'] = $request->file('award_image')->store('awards', 'public');
        }

        $award->update($validated);

        return redirect()->route('sponsor.awards.index')
            ->with('success', 'Award updated successfully!');
    }

    public function destroy(Award $award)
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();
        
        if ($award->sponsor_id !== $sponsor->id) {
            abort(403, 'Unauthorized');
        }

        if ($award->applications()->exists()) {
            return back()->with('error', 'Cannot delete award with existing applications.');
        }

        if ($award->award_image) {
            Storage::disk('public')->delete($award->award_image);
        }

        $award->delete();

        return redirect()->route('sponsor.awards.index')
            ->with('success', 'Award deleted successfully!');
    }
}