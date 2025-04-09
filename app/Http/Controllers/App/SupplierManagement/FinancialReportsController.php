<?php

namespace App\Http\Controllers\App\SupplierManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialReportsController extends Controller
{
    /**
     * Display revenue report.
     */
    public function revenue()
    {
        // Return revenue report view
        return view('tenant.suppliers.financial_reports.revenue');
    }

    /**
     * Display profit/loss report.
     */
    public function profitLoss()
    {
        // Return profit/loss report view
        return view('tenant.suppliers.financial_reports.profit_loss');
    }

    /**
     * Display expenses report.
     */
    public function expenses()
    {
        // Return expenses report view
        return view('tenant.suppliers.financial_reports.expenses');
    }

    /**
     * Display investments report.
     */
    public function investments()
    {
        // Return investments report view
        return view('tenant.suppliers.financial_reports.investments');
    }

    /**
     * Display cash flow report.
     */
    public function cashFlow()
    {
        // Return cash flow report view
        return view('tenant.suppliers.financial_reports.cash_flow');
    }
}