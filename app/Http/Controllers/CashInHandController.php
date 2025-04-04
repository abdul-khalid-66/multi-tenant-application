<?php

namespace App\Http\Controllers;

use App\Models\CashInHandDetail;
use Illuminate\Http\Request;

class CashInHandController extends Controller
{
    public function index()
    {
        return CashInHandDetail::with('reference')->latest()->paginate(20);
    }

    public function balance()
    {
        return ['balance' => CashInHandDetail::sum('amount')];
    }
}
