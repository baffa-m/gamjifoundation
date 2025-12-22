<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SponsorController extends Controller
{
    public function index(Request $request)
    {
        $query = Sponsor::with('user')->latest();

        if ($request->status) {
            $query->where('verification_status', $request->status);
        }

        if ($request->search) {
            $query->where('organization_name', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('email', 'like', "%{$request->search}%");
                  });
        }

        return Inertia::render('Admin/Sponsors/Index', [
            'sponsors' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['status', 'search'])
        ]);
    }

    public function show(Sponsor $sponsor)
    {
        return Inertia::render('Admin/Sponsors/Show', [
            'sponsor' => $sponsor->load(['user', 'awards' => function($q) {
                $q->withCount('applications');
            }])
        ]);
    }

    public function approve(Request $request, Sponsor $sponsor)
    {
        $sponsor->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verification_notes' => $request->notes
        ]);

        // Send notification to sponsor

        return back()->with('success', 'Sponsor approved successfully!');
    }

    public function reject(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'notes' => 'required|string'
        ]);

        $sponsor->update([
            'verification_status' => 'rejected',
            'verification_notes' => $validated['notes']
        ]);

        // Send notification to sponsor

        return back()->with('success', 'Sponsor rejected.');
    }

    public function suspend(Sponsor $sponsor)
    {
        $sponsor->update([
            'verification_status' => 'suspended'
        ]);

        return back()->with('success', 'Sponsor suspended.');
    }
}