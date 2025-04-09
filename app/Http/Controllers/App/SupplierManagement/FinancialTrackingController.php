<?php

namespace App\Http\Controllers\App\SupplierManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialTrackingController extends Controller
{
    /**
     * Display payment history.
     */
    public function paymentHistory()
    {
        // Return view with payment history
        return view('tenant.suppliers.financial_tracking.payments.index');
    }

    /**
     * Display payment details.
     */
    public function showPayment(string $id)
    {
        // Return view with single payment details
        return view('tenant.suppliers.financial_tracking.payments.show');
    }

    /**
     * Display outstanding balances.
     */
    public function outstandingBalances()
    {
        // Return view with outstanding balances
        return view('tenant.suppliers.financial_tracking.balances.index');
    }

    /**
     * Display balance details.
     */
    public function showBalance(string $id)
    {
        // Return view with single balance details
        return view('tenant.suppliers.financial_tracking.balances.show');
    }
}