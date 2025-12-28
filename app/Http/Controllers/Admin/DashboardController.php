<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Award, Application, Applicant, Sponsor};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Get applications per month for the current year
        // SQLite compatible query
        $applicationsPerMonth = Application::selectRaw("cast(strftime('%m', created_at) as integer) as month, count(*) as count")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $applicationsPerMonth[$i] ?? 0;
        }

        // Calculate trends (Growth compared to previous period)
        $getTrend = function($model) {
            $currentMonth = $model::where('created_at', '>=', now()->subDays(30))->count();
            $previousMonth = $model::where('created_at', '>=', now()->subDays(60))->where('created_at', '<', now()->subDays(30))->count();
            
            if ($previousMonth == 0) return $currentMonth > 0 ? 'New' : 0;
            return round((($currentMonth - $previousMonth) / $previousMonth) * 100, 1);
        };

        $trends = [
            'awards' => $getTrend(Award::class),
            'applications' => $getTrend(Application::class),
            'applicants' => $getTrend(Applicant::class),
            'sponsors' => $getTrend(Sponsor::class)
        ];

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
            'trends' => $trends,
            'chart_data' => $chartData,
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
