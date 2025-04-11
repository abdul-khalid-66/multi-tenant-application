<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    
    public function index()
    {
        $investments = Investment::orderBy('date', 'desc')
            ->paginate(20);
            
        return view('app.suppliers.investments.index', compact('investments'));
    }

    public function create()
    {
        $suppliers = Supplier::all(); // Get all suppliers
        return view('app.suppliers.investments.create', compact('suppliers'));
        // return view('app.suppliers.investments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $investment = Investment::create($validated);

        return redirect()->route('investments.show', $investment)
            ->with('success', 'Investment created successfully.');
    }

    public function show(Investment $investment)
    {
        return view('app.suppliers.investments.show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        $suppliers = Supplier::all(); // Get all suppliers
        return view('app.suppliers.investments.edit', compact('suppliers','investment'));
    }

    public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $investment->update($validated);

        return redirect()->route('investments.show', $investment)
            ->with('success', 'Investment updated successfully.');
    }

    public function destroy(Investment $investment)
    {
        $investment->delete();

        return redirect()->route('investments.index')
            ->with('success', 'Investment deleted successfully.');
    }
}
