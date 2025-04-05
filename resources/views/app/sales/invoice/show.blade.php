<x-tenant-app-layout>


    @include('app.sales.sidebar')

    <!-- Main Content -->
    <div class="content-area" id="contentArea">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-2xl font-semibold">Invoice #{{ $invoice->invoice_no }}</h2>
                                <p class="text-gray-500">Date: {{ $invoice->date->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('invoices.print', $invoice->id) }}" 
                                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                   target="_blank">
                                    Print
                                </a>

                                <a href="{{ route('invoices.pdf', $invoice->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                                    </svg>
                                    Download PDF
                                </a>
                                <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Back
                                </a>
                            </div>
                        </div>
        
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div>
                                <h3 class="text-lg font-medium mb-2">Business Information</h3>
                                <p>{{ config('settings.business_name') }}</p>
                                <p>{{ config('settings.business_address') }}</p>
                                <p>Phone: {{ config('settings.business_phone') }}</p>
                                <p>Email: {{ config('settings.business_email') }}</p>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium mb-2">Customer Information</h3>
                                <p>{{ $invoice->customer->name ?? 'Walk-in Customer' }}</p>
                                @if($invoice->customer)
                                <p>{{ $invoice->customer->address }}</p>
                                <p>Phone: {{ $invoice->customer->contact }}</p>
                                @endif
                            </div>
                        </div>
        
                        <div class="mb-8">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoice->saleDetails as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->variant->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ config('settings.currency_symbol') }}{{ number_format($item->sell_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ config('settings.currency_symbol') }}{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-lg font-medium mb-2">Payment Information</h3>
                                <p>Payment Method: {{ ucfirst($invoice->payment_method) }}</p>
                                <p>Status: 
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($invoice->payment_status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="flex justify-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>{{ config('settings.currency_symbol') }}{{ number_format($invoice->total_amount - $invoice->tax + $invoice->discount, 2) }}</span>
                                </div>
                                @if($invoice->discount > 0)
                                <div class="flex justify-between mb-2">
                                    <span>Discount:</span>
                                    <span class="text-red-500">-{{ config('settings.currency_symbol') }}{{ number_format($invoice->discount, 2) }}</span>
                                </div>
                                @endif
                                @if($invoice->tax > 0)
                                <div class="flex justify-between mb-2">
                                    <span>Tax:</span>
                                    <span>{{ config('settings.currency_symbol') }}{{ number_format($invoice->tax, 2) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                                    <span>Total:</span>
                                    <span>{{ config('settings.currency_symbol') }}{{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
</x-tenant-app-layout>


