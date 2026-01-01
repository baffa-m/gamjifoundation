<?php

use App\Http\Controllers\{WelcomeController, ProfileController, NewsController, AwardController};
use App\Http\Controllers\Admin;
use App\Http\Controllers\Applicant;
use App\Http\Controllers\Sponsor;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public Award Routes
Route::get('/awards', [AwardController::class, 'index'])->name('awards.index');
Route::get('/awards/{award}', [AwardController::class, 'show'])->name('awards.show');

// Public News routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Placeholder routes for Coming Soon features
Route::get('/scholarships', function() { return Inertia\Inertia::render('ComingSoon'); })->name('scholarships');
Route::get('/mentorship', function() { return Inertia\Inertia::render('ComingSoon'); })->name('mentorship');
Route::get('/stories', function() { return Inertia\Inertia::render('ComingSoon'); })->name('stories');
Route::get('/exam-prep', function() { return Inertia\Inertia::render('ComingSoon'); })->name('exam-prep');
Route::get('/career', function() { return Inertia\Inertia::render('ComingSoon'); })->name('career');
Route::get('/blog', function() { return Inertia\Inertia::render('ComingSoon'); })->name('blog');
Route::get('/contact', function() { return Inertia\Inertia::render('ComingSoon'); })->name('contact');
Route::get('/partner', function() { return Inertia\Inertia::render('ComingSoon'); })->name('partner');
Route::get('/privacy', function() { return Inertia\Inertia::render('ComingSoon'); })->name('privacy');
Route::get('/terms', function() { return Inertia\Inertia::render('ComingSoon'); })->name('terms');
Route::get('/cookies', function() { return Inertia\Inertia::render('ComingSoon'); })->name('cookies');

// Breeze auth routes
require __DIR__.'/auth.php';

// Profile routes (from Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('hero-slides', Admin\HeroSlideController::class);
    Route::resource('awards', Admin\AwardController::class);
    Route::resource('news', Admin\NewsController::class);
    Route::resource('applicants', Admin\ApplicantController::class)->only(['index', 'show', 'destroy']);
    
    Route::get('applications', [Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('applications/{application}', [Admin\ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('applications/{application}/review', [Admin\ApplicationController::class, 'review'])->name('applications.review');
    Route::patch('applications/{application}/approve', [Admin\ApplicationController::class, 'approve'])->name('applications.approve');
    Route::patch('applications/{application}/shortlist', [Admin\ApplicationController::class, 'shortlist'])->name('applications.shortlist');
    Route::patch('applications/{application}/reject', [Admin\ApplicationController::class, 'reject'])->name('applications.reject');
    
    Route::get('sponsors', [Admin\SponsorController::class, 'index'])->name('sponsors.index');
    Route::get('sponsors/{sponsor}', [Admin\SponsorController::class, 'show'])->name('sponsors.show');
    Route::patch('sponsors/{sponsor}/approve', [Admin\SponsorController::class, 'approve'])->name('sponsors.approve');
    Route::patch('sponsors/{sponsor}/reject', [Admin\SponsorController::class, 'reject'])->name('sponsors.reject');
    Route::patch('sponsors/{sponsor}/suspend', [Admin\SponsorController::class, 'suspend'])->name('sponsors.suspend');
});

// Applicant Routes
Route::middleware(['auth', 'role:applicant'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/profile/create', [Applicant\ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile/create', [Applicant\ProfileController::class, 'store'])->name('profile.store');
    Route::patch('/profile/update', [Applicant\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', [Applicant\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/awards', [Applicant\AwardController::class, 'index'])->name('awards.index');
    Route::get('/awards/{award}', [Applicant\AwardController::class, 'show'])->name('awards.show');
    
    Route::get('/applications', [Applicant\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [Applicant\ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/awards/{award}/apply', [Applicant\ApplicationController::class, 'store'])->name('applications.store');
});

// Sponsor Routes
Route::middleware(['auth', 'role:sponsor'])->prefix('sponsor')->name('sponsor.')->group(function () {
    Route::get('/dashboard', [Sponsor\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('awards', Sponsor\AwardController::class);
    
    Route::get('/register', [Sponsor\SponsorshipController::class, 'create'])->name('register');
    Route::post('/register', [Sponsor\SponsorshipController::class, 'store'])->name('register.store');
    Route::patch('/register', [Sponsor\SponsorshipController::class, 'update'])->name('register.update');
});

require __DIR__.'/auth.php';
