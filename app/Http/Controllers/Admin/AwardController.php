<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Award, Sponsor};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        $query = Award::with('sponsor.user')->withCount('applications');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        return Inertia::render('Admin/Awards/Index', [
            'awards' => $query->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only(['status', 'search', 'category'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Awards/Create', [
            'sponsors' => Sponsor::where('verification_status', 'verified')
                ->with('user')
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'sponsor_id' => 'nullable|exists:sponsors,id',
            'category' => 'required|in:jamb,waec,general,stem,arts,sports',
            'application_start_date' => 'required|date',
            'application_end_date' => 'required|date|after:application_start_date',
            'announcement_date' => 'nullable|date|after:application_end_date',
            'number_of_awards' => 'required|integer|min:1',
            'status' => 'required|in:draft,active,closed,suspended',
            'eligibility_criteria' => 'nullable|array',
            'required_documents' => 'nullable|string',
            'award_image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('award_image')) {
            $validated['award_image'] = $request->file('award_image')->store('awards', 'public');
        }

        Award::create($validated);

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award created successfully!');
    }

    public function show(Award $award)
    {
        return Inertia::render('Admin/Awards/Show', [
            'award' => $award->load(['sponsor.user', 'applications.applicant.user'])
        ]);
    }

    public function edit(Award $award)
    {
        return Inertia::render('Admin/Awards/Edit', [
            'award' => $award->load('sponsor'),
            'sponsors' => Sponsor::where('verification_status', 'verified')
                ->with('user')
                ->get()
        ]);
    }

    public function update(Request $request, Award $award)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'sponsor_id' => 'nullable|exists:sponsors,id',
            'category' => 'required|in:jamb,waec,general,stem,arts,sports',
            'application_start_date' => 'required|date',
            'application_end_date' => 'required|date',
            'announcement_date' => 'nullable|date',
            'number_of_awards' => 'required|integer|min:1',
            'status' => 'required|in:draft,active,closed,suspended',
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

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award updated successfully!');
    }

    public function destroy(Award $award)
    {
        if ($award->applications()->exists()) {
            return back()->with('error', 'Cannot delete award with existing applications.');
        }

        if ($award->award_image) {
            Storage::disk('public')->delete($award->award_image);
        }

        $award->delete();

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award deleted successfully!');
    }
}