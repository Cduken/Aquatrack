<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    // app/Http/Controllers/Admin/AdminDashboardController.php
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'total_users' => User::count(),
            'total_staffs' => User::role('staff')->count(),
            'total_reports' => Report::count(),
            'total_customers' => User::role('customer')->count(),

            'recent_activities' => Activity::latest()
                ->take(5)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'event' => $activity->event,
                        'description' => $activity->description,
                        'created_at' => $activity->created_at,
                        'causer_name' => $activity->causer?->name ?? 'System',
                        'properties' => $activity->properties,
                    ];
                }),
        ]);
    }

    protected function getActivityTitle($activity): string
    {
        return match ($activity->event) {
            'created' => "New {$activity->log_name} created",
            'updated' => "{$activity->log_name} updated",
            'deleted' => "{$activity->log_name} deleted",
            'logged_in' => "User logged in",
            'logged_out' => "User logged out",
            default => "System activity",
        };
    }

    protected function getActivityIcon($activity): string
    {
        return match ($activity->event) {
            'created' => 'bi-file-earmark-plus',
            'updated' => 'bi-pencil-square',
            'deleted' => 'bi-trash',
            'logged_in' => 'bi-box-arrow-in-right',
            'logged_out' => 'bi-box-arrow-left',
            default => 'bi-activity',
        };
    }

    protected function getActivityColor($activity): string
    {
        return match ($activity->event) {
            'created' => 'blue',
            'updated' => 'green',
            'deleted' => 'red',
            'logged_in' => 'purple',
            'logged_out' => 'gray',
            default => 'indigo',
        };
    }
}
