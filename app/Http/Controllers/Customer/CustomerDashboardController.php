<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Announcements;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerDashboardController extends Controller
{
    public function index()
    {

        $announcements = Announcements::count();

        return Inertia::render('Customer/Dashboard', [
            'announcements' => $announcements
        ]);
    }
}
