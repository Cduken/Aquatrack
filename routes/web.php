<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\CustomerAnnouncementsController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Roles\SelectRolesController;
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
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard redirection
Route::get('/redirect-to-dashboard', [AuthenticatedSessionController::class, 'redirectToDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('redirect-to-dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/reports', [ReportController::class, 'adminIndex'])->name('admin.reports');
    Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('admin.users');
    Route::delete('/admin/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/records', function () {
        return Inertia::render('Admin/Records');
    })->name('admin.records');

    Route::get('/admin/announcements', [AnnouncementsController::class, 'index'])->name('announcements');
    Route::post('/admin/announcements', [AnnouncementsController::class, 'store'])->name('announcements.store');
    Route::put('/admin/announcements/{announcement}', [AnnouncementsController::class, 'update'])->name('announcements.update');
    Route::delete('/admin/announcements/{announcement}', [AnnouncementsController::class, 'destroy'])->name('announcements.destroy');

    Route::get('/admin/staff', function () {
        return Inertia::render('Admin/Staff');
    })->name('admin.staff');
});

// Staff Routes
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', function () {
        return Inertia::render('Staff/Dashboard');
    })->name('staff.dashboard');

    Route::get('/staff/reading', function () {
        return Inertia::render('Staff/Reading');
    })->name('staff.reading');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/usage', function () {
        return Inertia::render('Customer/Usage');
    })->name('customer.usage');
    Route::get('/customer/reports', [ReportController::class, 'customerIndex'])->name('customer.reports');
    Route::get('/customer/announcements', [CustomerAnnouncementsController::class, 'index'])->name('customer.announcements');
});

//Select Roles
Route::get('/select-roles', [SelectRolesController::class, 'index'])->name('select-roles');

// Report Routes (Public and Authenticated)
Route::get('/reports', [ReportController::class, 'publicIndex'])->name('reports.index');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/success', [ReportController::class, 'success'])->name('reports.success');
Route::match(['get', 'post'], '/reports/track', [ReportController::class, 'track'])->name('reports.track');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

Route::get('/api/reports/find', [ReportController::class, 'findByTrackingCode'])->name('reports.find');


require __DIR__ . '/auth.php';
