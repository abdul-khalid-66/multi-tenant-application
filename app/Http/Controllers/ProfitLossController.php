<?php

namespace App\Http\Controllers;

use App\Models\ProfitLoss;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public function index()
    {
        return ProfitLoss::with('sale')->filter(request(['category', 'date_from']))->paginate(15);
    }

    public function show(ProfitLoss $profitLoss)
    {
        return $profitLoss->load('sale');
    }

    public function summary()
    {
        return [
            'total_profit' => ProfitLoss::sum('profit'),
            'total_loss' => ProfitLoss::sum('loss'),
            'net' => ProfitLoss::sum('profit') - ProfitLoss::sum('loss')
        ];
    }
}
