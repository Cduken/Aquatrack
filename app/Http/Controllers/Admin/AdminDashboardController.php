<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index() {



        $total_users = User::count();
        $total_staffs = User::role('staff')->count();
        $total_reports = Report::count();
        $total_customers = User::role('customer')->count();

        // dd($total_staffs);

        return Inertia::render('Admin/Dashboard', [
            'total_users' => $total_users,
            'total_staffs' => $total_staffs,
            'total_reports' => $total_reports,
            'total_customers' => $total_customers,
        ]);


    }

}
