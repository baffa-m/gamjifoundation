<?php

use App\Http\Controllers\{WelcomeController, ProfileController, NewsController};
use App\Http\Controllers\Admin;
use App\Http\Controllers\Applicant;
use App\Http\Controllers\Sponsor;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public News routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

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
    Route::patch('applications/{application}/reject', [Admin\ApplicationController::class, 'reject'])->name('applications.reject');
    
    Route::get('sponsors', [Admin\SponsorController::class, 'index'])->name('sponsors.index');
    Route::get('sponsors/{sponsor}', [Admin\SponsorController::class, 'show'])->name('sponsors.show');
    Route::patch('sponsors/{sponsor}/approve', [Admin\SponsorController::class, 'approve'])->name('sponsors.approve');
    Route::patch('sponsors/{sponsor}/reject', [Admin\SponsorController::class, 'reject'])->name('sponsors.reject');
    Route::patch('sponsors/{sponsor}/suspend', [Admin\SponsorController::class, 'suspend'])->name('sponsors.suspend');
});

// Applicant Routes
Route::middleware(['auth', 'role:applicant'])->prefix('applicant')->name('applicant.')->group(function () {
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
});

require __DIR__.'/auth.php';
