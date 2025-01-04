<?php

namespace App\Http\Controllers;

use App\Models\ProductReport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You must be logged in to report a product'
                ], 401);
            }

            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'reason' => 'required|string|max:255',
                'description' => 'required|string'
            ]);

            // Check if user has already reported this product
            $existingReport = ProductReport::where('user_id', auth()->id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingReport) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have already reported this product'
                ], 400);
            }

            // Create the report
            $report = ProductReport::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'reason' => trim($request->reason),
                'description' => trim($request->description),
                'status' => 'pending'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product has been reported successfully. Our team will review it.',
                'report' => $report
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Product Report Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'There was an error submitting your report. Please try again.'
            ], 500);
        }
    }

    public function adminIndex()
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return redirect('/home')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $reports = ProductReport::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.index', compact('reports'));
    }

    public function adminShow(ProductReport $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    public function adminUpdate(Request $request, ProductReport $report)
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return redirect('/home')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_notes' => 'nullable|string'
        ]);

        $oldNotes = $report->admin_notes;
        $newNotes = $request->admin_notes;

        // Load the product relationship
        $report->load('product');

        // Update the report
        $report->update([
            'status' => $request->status,
            'admin_notes' => $newNotes
        ]);

        // If admin notes were added or changed, store a notification in session
        if (($oldNotes === null && $newNotes !== null) || ($oldNotes !== $newNotes)) {
            if ($report->product) {
                Session::flash('report_notification_' . $report->product->seller_id, [
                    'product_id' => $report->product->id,
                    'message' => 'New admin feedback available for your product: ' . $report->product->title,
                    'report_id' => $report->id
                ]);

                // Log the notification for debugging
                Log::info('Admin Notes Notification Created', [
                    'seller_id' => $report->product->seller_id,
                    'product_id' => $report->product->id,
                    'report_id' => $report->id,
                    'notes' => $newNotes
                ]);
            }
        }

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report updated successfully with notes');
    }

    public function updateStatus(Request $request, ProductReport $report)
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return response()->json([
                'message' => 'Unauthorized access. Admin privileges required.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_notes' => 'nullable|string'
        ]);

        $oldNotes = $report->admin_notes;
        $newNotes = $request->admin_notes;

        $report->update([
            'status' => $request->status,
            'admin_notes' => $newNotes
        ]);

        // If admin notes were added or changed, store a notification in session
        if (($oldNotes === null && $newNotes !== null) || ($oldNotes !== $newNotes)) {
            $product = $report->product;
            if ($product) {
                Session::flash('report_notification_' . $product->seller_id, [
                    'product_id' => $product->id,
                    'message' => 'New admin feedback available for your product: ' . $product->title,
                    'report_id' => $report->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Report status updated successfully',
            'report' => $report
        ]);
    }

    public function deleteProduct($id)
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($id);
            
            // Delete the product (this will trigger the boot method in Product model)
            $product->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product:', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the product'
            ], 500);
        }
    }
}
