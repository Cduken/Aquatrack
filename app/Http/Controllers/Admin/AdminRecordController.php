<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeterReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminRecordController extends Controller
{
    public function index(Request $request)
    {
        // Build the query with relationships
        $query = MeterReading::with('user');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Apply month filter
        if ($request->has('month') && !empty($request->month)) {
            $query->whereMonth('reading_date', $request->month);
        }

        // Apply year filter
        if ($request->has('year') && !empty($request->year)) {
            $query->whereYear('reading_date', $request->year);
        }

        // Apply sorting
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Get paginated results
        $perPage = $request->get('perPage', 10);
        $records = $query->paginate($perPage);

        $serial_number = Auth::user()->serial_number;

        return Inertia::render('Admin/Records', [
            'serial_number' => $serial_number,
            'records' => $records,
            'filters' => $request->only(['search', 'status', 'month', 'year', 'perPage']),
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function show(MeterReading $record)
    {
        $record->load('user');

        // Return JSON if it's an AJAX request (for modal)
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json($record);
        }

        // Otherwise return the Inertia page
        return Inertia::render('Admin/Records/Show', [
            'record' => $record,
        ]);
    }

    public function edit(MeterReading $record)
    {
        $record->load('user');
        return Inertia::render('Admin/Records/Edit', [
            'record' => $record,
        ]);
    }

    public function update(Request $request, MeterReading $record)
    {
        $validated = $request->validate([
            'reading' => 'sometimes|numeric',
            'consumption' => 'sometimes|numeric',
            'amount' => 'sometimes|numeric',
            'status' => 'sometimes|in:Paid,Pending,Overdue'
        ]);

        $record->update($validated);

        return redirect()->route('admin.records.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(MeterReading $record)
    {
        $record->delete();

        return redirect()->back()
            ->with('success', 'Record deleted successfully.');
    }


    public function details(MeterReading $record)
    {
        $record->load('user');
        return response()->json($record);
    }
}
