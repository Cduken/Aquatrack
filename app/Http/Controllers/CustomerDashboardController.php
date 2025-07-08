<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // You can pull this from the database later!
        return Inertia::render('Customer/Dashboard', [
            'userName' => 'John Doe',
            'location' => 'Clarin, Bohol',
            'reading' => 24567,
            'monthlyUsage' => 1322,
            'usageChange' => -5,
            'activeReports' => 2,
            'pendingReports' => 1,
        ]);
    }
}
