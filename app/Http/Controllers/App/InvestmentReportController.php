<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

class InvestmentReportController extends Controller
{
    public function summary()
    {
        $totalInvestments = Investment::sum('amount');
        $investmentsByType = Investment::groupBy('type')
            ->selectRaw('type, sum(amount) as total')
            ->get();
            
        return view('app.suppliers.investments.summary', compact('totalInvestments', 'investmentsByType'));
    }

    public function returns()
    {
        $investments = Investment::with('supplier')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('app.investments.reports.returns', compact('investments'));
    }
}