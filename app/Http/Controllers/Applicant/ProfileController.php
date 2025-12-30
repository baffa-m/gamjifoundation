<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function create()
    {
        // Check if user already has an applicant profile
        $existingApplicant = Applicant::where('user_id', auth()->id())->first();

        if ($existingApplicant) {
            return redirect()->route('applicant.dashboard')
                ->with('message', 'You already have an applicant profile.');
        }

        $locations = \App\Models\State::with('lgas')->get();

        return Inertia::render('Applicant/Profile/Create', [
            'locations' => $locations
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'state_of_origin' => 'required|string',
            'lga' => 'required|string',
            'school_name' => 'required|string',
            'jamb_reg_number' => 'nullable|string',
            'waec_reg_number' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['email'] = auth()->user()->email; // Sync email from user account

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('applicants/photos', 'public');
        }

        Applicant::create($validated);

        return redirect()->route('applicant.dashboard')
            ->with('success', 'Profile created successfully! You can now apply for awards.');
    }

    public function update(Request $request)
    {
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'state_of_origin' => 'required|string',
            'lga' => 'required|string',
            'school_name' => 'required|string',
            'jamb_reg_number' => 'nullable|string',
            'waec_reg_number' => 'nullable|string',
            'waec_results' => 'nullable|array',
            'waec_results.*.subject' => 'required_with:waec_results|string|max:255',
            'waec_results.*.grade' => 'required_with:waec_results|string|max:5',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($applicant->profile_picture) {
                Storage::disk('public')->delete($applicant->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('applicants/photos', 'public');
        }

        $applicant->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
