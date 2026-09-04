<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReport;
use App\Models\StoreReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReport::with(['reporter', 'product']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        $productReports = $query->latest()->paginate(15);

        return view('admin.reports.product-reports', compact('productReports'));
    }

    public function show(ProductReport $report)
    {
        return view('admin.reports.product-report-show', compact('report'));
    }

    public function review(Request $request, ProductReport $report)
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

        // If rejected, suspend the product
        if ($validated['status'] === 'rejected') {
            $report->product->status = 'suspended';
            $report->product->save();
        }

        return back()->with('success', 'Product report ' . $validated['status'] . ' successfully.');
    }
}