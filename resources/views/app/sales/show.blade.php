<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Sale Invoice #{{ $sale->invoice_no }}</h2>
                            <div class="flex space-x-2">
                                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                    Back to Sales
                                </a>
                                <button onclick="window.print()" class="btn btn-primary">
                                    Print Invoice
                                </button>
                            </div>
                        </div>

                        <!-- Invoice Header -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <h3 class="text-lg font-medium mb-2">From</h3>
                                <p class="text-gray-700">
                                    <strong>Your Business Name</strong><br>
                                    123 Business Street<br>
                                    City, State 10001<br>
                                    Phone: (123) 456-7890<br>
                                    Email: info@yourbusiness.com
                                </p>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-2">To</h3>
                                <p class="text-gray-700">
                                    <strong>{{ $sale->customer->name }}</strong><br>
                                    {{ $sale->customer->address ?? 'N/A' }}<br>
                                    Phone: {{ $sale->customer->contact }}<br>
                                    Email: {{ $sale->customer->email ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-2">Invoice Details</h3>
                                <p class="text-gray-700">
                                    <strong>Invoice #:</strong> {{ $sale->invoice_no }}<br>
                                    <strong>Date:</strong> {{ $sale->date->format('M d, Y h:i A') }}<br>
                                    <strong>Status:</strong> 
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($sale->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="mb-8 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($sale->saleDetails as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $item->variant?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${{ number_format($item->sell_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->unit ?? 'pcs' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ${{ number_format($item->total_price, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-start-3">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="font-medium">Subtotal:</span>
                                            <span>${{ number_format($sale->total_amount + $sale->discount - $sale->tax, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">Discount:</span>
                                            <span>-${{ number_format($sale->discount, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">Tax:</span>
                                            <span>${{ number_format($sale->tax, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                                            <span>Total:</span>
                                            <span>${{ number_format($sale->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method & Notes -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium mb-2">Payment Method</h3>
                                <p class="text-gray-700 capitalize">{{ $sale->payment_method }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium mb-2">Notes</h3>
                                <p class="text-gray-700">{{ $sale->notes ?? 'No notes available' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #contentArea, #contentArea * {
                visibility: visible;
            }
            #contentArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
    @endpush
</x-tenant-app-layout>