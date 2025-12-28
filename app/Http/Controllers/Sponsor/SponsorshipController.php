<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SponsorshipController extends Controller
{
    public function create()
    {
        // Check if user already has a sponsor profile
        $existingSponsor = Sponsor::where('user_id', auth()->id())->first();
        
        if ($existingSponsor) {
            return redirect()->route('sponsor.dashboard')
                ->with('message', 'You already have a sponsor profile.');
        }

        return Inertia::render('Sponsor/Register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'sponsor_type' => 'required|in:individual,corporate,government,ngo',
            'registration_number' => 'nullable|string',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'address' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['verification_status'] = 'pending';

        Sponsor::create($validated);

        return redirect()->route('sponsor.dashboard')
            ->with('success', 'Sponsor profile created! Awaiting admin verification.');
    }

    public function update(Request $request)
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'sponsor_type' => 'required|in:individual,corporate,government,ngo',
            'registration_number' => 'nullable|string',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'address' => 'required|string',
        ]);

        $sponsor->update($validated);

        return back()->with('success', 'Sponsor profile updated successfully.');
    }
}