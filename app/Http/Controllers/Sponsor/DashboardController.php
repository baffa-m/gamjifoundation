<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\{Sponsor, Award, Application};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $sponsor = Sponsor::where('user_id', auth()->id())->first();

        if (!$sponsor) {
            // Redirect to sponsor registration
            return redirect()->route('sponsor.register')
                ->with('message', 'Please complete your sponsor profile.');
        }

        $awards = Award::where('sponsor_id', $sponsor->id)->get();
        $totalApplications = Application::whereIn('award_id', $awards->pluck('id'))->count();

        return Inertia::render('Sponsor/Dashboard', [
            'sponsor' => $sponsor,
            'stats' => [
                'total_awards' => $awards->count(),
                'active_awards' => $awards->where('status', 'active')->count(),
                'total_applications' => $totalApplications,
                'pending_applications' => Application::whereIn('award_id', $awards->pluck('id'))
                    ->where('application_status', 'pending')
                    ->count(),
            ],
            'recent_awards' => Award::where('sponsor_id', $sponsor->id)
                ->withCount('applications')
                ->latest()
                ->take(5)
                ->get(),
            'verification_status' => $sponsor->verification_status
        ]);
    }
}
