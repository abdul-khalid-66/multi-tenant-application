<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\CashInHandDetail;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnApprovalController extends Controller
{
    public function index()
    {

        $returns = ProductReturn::with(['customer', 'sale', 'returnDetails'])
            ->where('status', 'pending')
            ->orderBy('return_date', 'asc')
            ->paginate(10);

        return view('app.sales.returns.approval_index', compact('returns'));
    }

    public function show(ProductReturn $return)
    {
        $return->load(['customer', 'sale.customer', 'returnDetails.product', 'returnDetails.variant']);

        return view('app.sales.returns.approval-show', compact('return'));
    }

    public function approve(ProductReturn $return)
    {
        return view('app.sales.returns.approval_action', [
            'return' => $return,
            'action' => 'approve',
            'title' => 'Approve Return'
        ]);
    }

    public function reject(ProductReturn $return)
    {
        return view('app.sales.returns.approval_action', [
            'return' => $return,
            'action' => 'reject',
            'title' => 'Reject Return'
        ]);
    }

    public function process(Request $request, ProductReturn $return)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            if ($validated['action'] === 'approve') {
                // Process approval - observer will handle stock updates
                $return->update([
                    'status' => 'approved',
                    'notes' => $validated['notes'] ?? null
                ]);

                // Record cash movement
                CashInHandDetail::create([
                    'date' => now(),
                    'amount' => -$return->total_refund_amount,
                    'transaction_type' => 'refund',
                    'reference_id' => $return->id,
                    'notes' => 'Refund for return #' . $return->id
                ]);
            } else {
                // Process rejection
                $return->update([
                    'status' => 'rejected',
                    'notes' => $validated['notes'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('returns.pending')
                ->with('success', 'Return has been ' . $validated['action'] . 'ed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process return: ' . $e->getMessage());
        }
    }
}
