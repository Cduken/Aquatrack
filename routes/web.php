<?php

use App\Http\Controllers\Admin\StaffController;
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


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/reports', function () {
        return Inertia::render('Admin/Reports');
    })->name('admin.reports');

    Route::get('/admin/users', function () {
        return Inertia::render('Admin/Users');
    })->name('admin.users');

    Route::get('/admin/records', function () {
        return Inertia::render('Admin/Records');
    })->name('admin.records');

    Route::get('/admin/announcements', function () {
        return Inertia::render('Admin/Announcements');
    })->name('admin.announcements');

    Route::get('/admin/staff', function () {
        return Inertia::render('Admin/Staff');
    })->name('admin.staff');
});

//Staff Routes
Route::middleware(['auth', 'role:staff'])->group(function () {

    Route::get('/staff/dashboard', function () {
        return Inertia::render('Staff/Dashboard');
    })->middleware(['auth', 'role:staff'])->name('staff.dashboard');

    Route::get('/staff/reading', function () {
        return Inertia::render('Staff/Reading');
    })->name('staff.reading');
});


//Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return Inertia::render('Customer/Dashboard');
    })->name('customer.dashboard');

    Route::get('/customer/usage', function () {
        return Inertia::render('Customer/Usage');
    })->name('customer.usage');

    Route::get('/customer/reports', function () {
        return Inertia::render('Customer/Reports');
    })->name('customer.dashboard');

    Route::get('/customer/announcements', function () {
        return Inertia::render('Customer/Announcements');
    })->name('customer.announcements');
});

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');



require __DIR__ . '/auth.php';
