<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MeterReading;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CustomerUsageController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch meter readings for the authenticated user
        $readings = MeterReading::where('user_id', $user->id)
            ->orderBy('reading_date', 'desc')
            ->limit(12)
            ->get()
            ->map(function ($reading) {
                return [
                    'month' => $reading->billing_month . ' ' . $reading->reading_date->format('Y'),
                    'usage' => $reading->consumption,
                    'amount' => number_format($reading->amount, 2),
                    'status' => $reading->amount > 0 ? 'Pending' : 'Paid', // Simplified status logic
                ];
            });

        // Prepare chart data
        $chartData = MeterReading::where('user_id', $user->id)
            ->orderBy('reading_date', 'asc')
            ->limit(6)
            ->get()
            ->map(function ($reading) {
                return [
                    'month' => $reading->billing_month,
                    'usage' => $reading->consumption,
                    'amount' => $reading->amount,
                ];
            });



        return Inertia::render('Customer/Usage', [
            'usageData' => $readings,
            'chartData' => $chartData,
        ]);
    }
}
