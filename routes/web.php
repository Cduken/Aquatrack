<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing/LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Add these new routes
Route::get('/redirect-to-dashboard', [AuthenticatedSessionController::class, 'redirectToDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('redirect-to-dashboard');

Route::get('/admin/dashboard', function () {
    return Inertia::render('Admin/Dashboard', [
    ]);
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::get('/staff/dashboard', function () {
    return Inertia::render('Staff/Dashboard');
})->middleware(['auth', 'role:staff'])->name('staff.dashboard');

// Keep your existing dashboard route for customers
Route::get('/customer/dashboard', function () {
    return Inertia::render('Customer/Dashboard');
})->middleware(['auth', 'role:customer'])->name('customer.dashboard');


Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');



require __DIR__ . '/auth.php';
