<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return Expense::paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'verified_by' => 'nullable|string|max:100'
        ]);

        return Expense::create($data);
    }

    public function show(Expense $expense)
    {
        return $expense;
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string|max:50',
            'description' => 'nullable|string',
            'date' => 'sometimes|date',
            'verified_by' => 'nullable|string|max:100'
        ]);

        $expense->update($data);
        return $expense;
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->noContent();
    }
}
