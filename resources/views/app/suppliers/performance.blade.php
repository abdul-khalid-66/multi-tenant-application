<x-tenant-app-layout>
    @include('app.suppliers.sidebar')
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Supplier Performance: {{ $supplier->name }}</h2>
                            <a href="{{-- route('suppliers.show', $supplier) --}}" class="btn btn-secondary">
                                Back to Supplier
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h3 class="text-lg font-medium text-blue-800">Products Supplied</h3>
                                <p class="text-3xl font-bold text-blue-600">{{ $metrics['product_count'] }}</p>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <h3 class="text-lg font-medium text-green-800">Inventory Changes</h3>
                                <p class="text-3xl font-bold text-green-600">{{ $metrics['inventory_changes'] }}</p>
                            </div>
                            
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                <h3 class="text-lg font-medium text-purple-800">Current Stock Value</h3>
                                <p class="text-3xl font-bold text-purple-600">${{ number_format($metrics['current_stock_value'], 2) }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Recent Inventory Changes</h3>
                            @if($supplier->inventoryLogs()->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="text-left px-5">Product</th>
                                                <th class="text-left px-5">Variant</th>
                                                <th class="text-left px-5">Change</th>
                                                <th class="text-left px-5">Reason</th>
                                                <th class="text-left px-5">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($supplier->inventoryLogs()->latest()->take(5)->get() as $log)
                                            <tr>
                                                <td class="px-5 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->product->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-5 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->variant->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-5 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->new_stock - $log->old_stock }}
                                                </td>
                                                <td class="px-5 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->reason }}
                                                </td>
                                                <td class="px-5 py-4 text-left whitespace-nowrap text-sm text-gray-500">
                                                    {{ $log->date instanceof \Carbon\Carbon ? $log->date->format('Y-m-d H:i') : \Carbon\Carbon::parse($log->date)->format('Y-m-d H:i') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500">No inventory changes recorded for this supplier.</p>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>