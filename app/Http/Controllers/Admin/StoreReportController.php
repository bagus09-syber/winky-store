<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreReportController extends Controller
{
    public function index(Request $request)
    {
        $query = StoreReport::with(['reporter', 'store']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        $storeReports = $query->latest()->paginate(15);

        return view('admin.reports.store-reports', compact('storeReports'));
    }

    public function show(StoreReport $report)
    {
        return view('admin.reports.store-report-show', compact('report'));
    }

    public function review(Request $request, StoreReport $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:resolved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $report->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // If rejected, suspend the store
        if ($validated['status'] === 'rejected') {
            $report->store->status = 'suspended';
            $report->store->save();
        }

        return back()->with('success', 'Store report ' . $validated['status'] . ' successfully.');
    }
}