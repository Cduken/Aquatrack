<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRecordController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\CustomerAnnouncementsController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerUsageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Roles\SelectRolesController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffReadingController;
use App\Models\User;
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
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard redirection
Route::get('/redirect-to-dashboard', [AuthenticatedSessionController::class, 'redirectToDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('redirect-to-dashboard');

Route::post('/verify-code', [AuthenticatedSessionController::class, 'verifyCode'])->name('verify-code');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/reports', [ReportController::class, 'adminIndex'])->name('admin.reports');

    Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [AdminUsersController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/toggle-status', [AdminUsersController::class, 'toggleStatus'])->name('admin.users.toggle-status');

    Route::post('/admin/reports/{report}/update-status', [ReportController::class, 'updateStatus'])
        ->name('admin.reports.updateStatus');

    Route::get('/admin/records', [AdminRecordController::class, 'index'])->name('admin.records.index');
    Route::get('/admin/records/{record}', [AdminRecordController::class, 'show'])->name('admin.records.show');
    Route::get('/admin/records/{record}/edit', [AdminRecordController::class, 'edit'])->name('admin.records.edit');
    Route::put('/admin/records/{record}', [AdminRecordController::class, 'update'])->name('admin.records.update');
    Route::delete('/admin/records/{record}', [AdminRecordController::class, 'destroy'])->name('admin.records.destroy');

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
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/dashboard/data', [StaffDashboardController::class, 'getDashboardData'])->name('staff.dashboard.data');

    // Meter reading routes
    Route::get('/staff/reading', [StaffReadingController::class, 'index'])->name('staff.reading');
    Route::get('/staff/reading/search', [StaffReadingController::class, 'search'])->name('staff.reading.search');
    Route::get('/staff/reading/user/{userId}', [StaffReadingController::class, 'getUserDetails'])->name('staff.reading.user');
    Route::get('/staff/reading/previous/{userId}', [StaffReadingController::class, 'getPreviousReadings'])->name('staff.reading.previous');
    Route::post('/staff/reading', [StaffReadingController::class, 'storeReading'])->name('staff.reading.store');
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/usage', [CustomerUsageController::class, 'index'])->name('customer.usage');
    Route::get('/customer/reports', [ReportController::class, 'customerIndex'])->name('customer.reports');
    Route::get('/customer/announcements', [CustomerAnnouncementsController::class, 'index'])->name('customer.announcements');
});

// Select Roles
Route::get('/select-roles', [SelectRolesController::class, 'index'])->name('select-roles');

// Report Routes (Public and Authenticated)
Route::get('/reports', [ReportController::class, 'publicIndex'])->name('reports.index');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/success', [ReportController::class, 'success'])->name('reports.success');
Route::match(['get', 'post'], '/reports/track', [ReportController::class, 'track'])->name('reports.track');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
Route::get('/api/reports/find', [ReportController::class, 'findByTrackingCode'])->name('reports.find');
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');

Route::get('/debug/customers', function () {
    return User::where('role', 'customer')->get();
});

require __DIR__ . '/auth.php';
