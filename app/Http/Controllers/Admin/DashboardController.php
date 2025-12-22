<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Award, Application, Applicant, Sponsor};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_awards' => Award::count(),
                'active_awards' => Award::active()->count(),
                'draft_awards' => Award::draft()->count(),
                'suspended_awards' => Award::suspended()->count(),
                
                'total_applications' => Application::count(),
                'pending_applications' => Application::pending()->count(),
                'under_review' => Application::underReview()->count(),
                'approved_applications' => Application::approved()->count(),
                'rejected_applications' => Application::rejected()->count(),
                
                'total_applicants' => Applicant::count(),
                
                'total_sponsors' => Sponsor::count(),
                'pending_sponsors' => Sponsor::where('verification_status', 'pending')->count(),
                'verified_sponsors' => Sponsor::where('verification_status', 'verified')->count(),
            ],
            'recent_applications' => Application::with(['applicant.user', 'award'])
                ->latest()
                ->take(5)
                ->get(),
            'recent_awards' => Award::with('sponsor')->latest()->take(5)->get(),
            'pending_sponsors' => Sponsor::with('user')
                ->where('verification_status', 'pending')
                ->latest()
                ->take(5)
                ->get()
        ]);
    }
}
