public function index()
    {
        $investments = Investment::with('supplier')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('app.investments.index', compact('investments'));
    }

    public function create()
    {
        return view('app.investments.create');
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
        return view('app.investments.show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        return view('app.investments.edit', compact('investment'));
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





    another step is 

    public function index()
    {
        $investments = Investment::with('supplier')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('app.investments.index', compact('investments'));
    }

    public function create()
    {
        return view('app.investments.create');
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
        return view('app.investments.show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        return view('app.investments.edit', compact('investment'));
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

    4.1 Main Layout (resources/views/app/Sales/investments/index.blade.php)


    <x-tenant-app-layout>
    @include('app.sales.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Investments</h2>
                        <div>
                            <a href="{{ route('investments.create') }}" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i> Add Investment
                            </a>
                            <a href="{{ route('investments.reports.summary') }}" class="btn-secondary ml-2">
                                <i class="fas fa-chart-pie mr-2"></i> Reports
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($investments as $investment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $investment->date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ format_currency($investment->amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $investment->type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $investment->supplier->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('investments.show', $investment) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('investments.edit', $investment) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('investments.destroy', $investment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $investments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>

4.2 Create/Edit Form (resources/views/app/salses/investments/form.blade.php)

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $investment->amount ?? '') }}"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('amount')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
            <select name="type" id="type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select Type</option>
                <option value="initial" @selected(old('type', $investment->type ?? '') == 'initial')>Initial Investment</option>
                <option value="additional" @selected(old('type', $investment->type ?? '') == 'additional')>Additional Investment</option>
                <option value="loan" @selected(old('type', $investment->type ?? '') == 'loan')>Loan</option>
                <option value="other" @selected(old('type', $investment->type ?? '') == 'other')>Other</option>
            </select>
            @error('type')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
        <input type="date" name="date" id="date" value="{{ old('date', isset($investment) ? $investment->date->format('Y-m-d') : now()->format('Y-m-d')) }}"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('date')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier (Optional)</label>
        <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="">No Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $investment->supplier_id ?? '') == $supplier->id)>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
        @error('supplier_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $investment->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>


4.3 Create View (resources/views/app/sales/investments/create.blade.php)

<x-tenant-app-layout>
    @include('app.sales.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Add New Investment</h2>
                        <a href="{{ route('investments.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Back to List
                        </a>
                    </div>

                    <form action="{{ route('investments.store') }}" method="POST">
                        @csrf
                        @include('app.investments.form')
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Save Investment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>

4.4 Create View (resources/views/app/sales/investments/show.blade.php)<x-tenant-app-layout>
    @include('app.sales.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Investment Details</h2>
                        <div>
                            <a href="{{ route('investments.edit', $investment) }}" class="btn-secondary mr-2">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <a href="{{ route('investments.index') }}" class="btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                                <div class="mt-2 border-t border-gray-200 pt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Amount:</span>
                                        <span class="text-sm text-gray-900">{{ format_currency($investment->amount) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Type:</span>
                                        <span class="text-sm text-gray-900">{{ ucfirst($investment->type) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Date:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->date->format('d M Y') }}</span>
                                    </div>
                                    @if($investment->supplier)
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Supplier:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->supplier->name }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Description</h3>
                                <div class="mt-2 border-t border-gray-200 pt-2">
                                    <p class="text-sm text-gray-700">{{ $investment->description ?? 'No description provided.' }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">System Information</h3>
                                <div class="mt-2 border-t border-gray-200 pt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Created At:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Updated At:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->updated_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>

4.5 Report Views (resources/views/app/sales/investments/reports/summary.blade.php)

<x-tenant-app-layout>
    @include('app.sales.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Investment Summary Report</h2>
                        <a href="{{ route('investments.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Investments
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <h3 class="text-sm font-medium text-blue-800">Total Investments</h3>
                            <p class="mt-1 text-2xl font-semibold text-blue-900">{{ format_currency($totalInvestments) }}</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Investments by Type</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($investmentsByType as $type)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ucfirst($type->type) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ format_currency($type->total) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format(($type->total / $totalInvestments) * 100, 2) }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>


controller
2. Controller (app/Http/Controllers/App/InvestmentController.php)

<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::with('supplier')
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('app.investments.index', compact('investments'));
    }

    public function create()
    {
        return view('app.investments.create');
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
        return view('app.investments.show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        return view('app.investments.edit', compact('investment'));
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
            
        return view('app.investments.reports.summary', compact('totalInvestments', 'investmentsByType'));
    }

    public function returns()
    {
        $investments = Investment::with('supplier')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('app.investments.reports.returns', compact('investments'));
    }
}