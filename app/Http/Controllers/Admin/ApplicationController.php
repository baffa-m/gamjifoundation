<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['applicant.user', 'award'])->latest();

        if ($request->status) {
            $query->where('application_status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('applicant', function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->award_id) {
            $query->where('award_id', $request->award_id);
        }

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['status', 'search', 'award_id']),
            'awards' => \App\Models\Award::select('id', 'title')->get()
        ]);
    }

    public function show(Application $application)
    {
        return Inertia::render('Admin/Applications/Show', [
            'application' => $application->load(['applicant.user', 'award.sponsor'])
        ]);
    }

    public function review(Request $request, Application $application)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string'
        ]);

        $application->update([
            'application_status' => 'under_review',
            'admin_notes' => $validated['admin_notes'] ?? null
        ]);

        return back()->with('success', 'Application marked as under review.');
    }

    public function approve(Request $request, Application $application)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string'
        ]);

        $application->update([
            'application_status' => 'accepted',
            'admin_notes' => $validated['admin_notes'] ?? null
        ]);

        // You can send notification to applicant here

        return back()->with('success', 'Application approved successfully!');
    }

    public function reject(Request $request, Application $application)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string'
        ]);

        $application->update([
            'application_status' => 'rejected',
            'admin_notes' => $validated['admin_notes']
        ]);

        // You can send notification to applicant here

        return back()->with('success', 'Application rejected.');
    }
}