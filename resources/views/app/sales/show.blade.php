<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Sale Invoice #{{ $sale->invoice_no }}</h2>


                            <div class="flex space-x-3">
                                <!-- Print Button (opens in new tab) -->
                                <a href="{{ route('sales.print', $sale->id) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                   target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                                    </svg>
                                    Print
                                </a>
                                
                                <!-- Download PDF Button -->
                                <a href="{{ route('sales.invoice.pdf', $sale->id) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Download PDF
                                </a>
                                
                                <!-- Back Button -->
                                <a href="{{ route('sales.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Back
                                </a>
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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $subtotal = 0;
                                        $totalQuantity = $sale->saleDetails->sum('quantity');
                                        $discountPerItem = $totalQuantity > 0 ? $sale->discount / $totalQuantity : 0;
                                        $taxPerItem = $totalQuantity > 0 ? $sale->tax / $totalQuantity : 0;

                                        // $unitPrice = 0;
                                        $unitQuantity = 0;
                                        $subDiscount = 0;
                                        $subtex = 0;
                                    @endphp
                                    
                                    @foreach($sale->saleDetails as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $item->variant?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rs. {{ number_format($item->sell_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rs. {{ number_format($discountPerItem * $item->quantity, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Rs. {{ number_format($taxPerItem * $item->quantity, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->unit ?? 'pcs' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rs. {{ number_format(($item->sell_price * $item->quantity) - ($discountPerItem * $item->quantity) + ($taxPerItem * $item->quantity), 2) }}
                                        </td>
                                    </tr>
                                    @php
                                        // $unitPrice += $item->sell_price;
                                        $unitQuantity += $item->quantity;
                                        $subDiscount += $discountPerItem * $item->quantity;
                                        $subtex += $taxPerItem * $item->quantity;
                                        $subtotal += ($item->sell_price * $item->quantity) - ($discountPerItem * $item->quantity) + ($taxPerItem * $item->quantity);
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"> </td>
                                        <td colspan="1" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"> Total</td>
                                        <td colspan="1" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"> {{ number_format($unitQuantity) }}</td>
                                        <td colspan="1" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">  {{ number_format($subDiscount, 2) }}</td>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">  {{ number_format($subtex, 2) }}</td>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">  {{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-start-3">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                                            <span>Total:</span>
                                            <span>Rs.{{ number_format($sale->total_amount + $sale->tex - $sale->descount, 2) }}</span>
                                        </div>                                       
                                        <div class="flex justify-between">
                                            <span class="font-medium">Discount:</span>
                                            <span>Rs. {{ number_format($sale->discount, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">Tax:</span>
                                            <span>Rs. {{ number_format($sale->tax, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">Subtotal:</span>
                                            <span>Rs.{{ number_format($subtotal, 2) }}</span>
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

                        <!-- Payment Status Update Section -->
                        @if($sale->payment_status != 'paid')
                        <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Payment Update</h3>
                            
                            <form action="{{ route('sales.updatePayment', $sale->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Current Status -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Current Status</label>
                                        <div class="mt-1 p-2 bg-gray-100 rounded">
                                            @if($sale->payment_status == 'paid')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Paid</span>
                                            @elseif($sale->payment_status == 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Pending</span>
                                            @else
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Partial (Rs. {{ number_format($sale->amount_paid, 2) }}/{{ number_format($sale->total_amount, 2) }})</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- New Status -->
                                    <div>
                                        <label for="payment_status" class="block text-sm font-medium text-gray-700">New Status *</label>
                                        <select name="payment_status" id="payment_status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            <option value="paid" {{ $sale->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="pending" {{ $sale->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="partial" {{ $sale->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Amount Paid (for partial) -->
                                    <div id="amountPaidField" style="{{ $sale->payment_status != 'partial' ? 'display: none;' : '' }}">
                                        <label for="amount_paid" class="block text-sm font-medium text-gray-700">Amount Paid *</label>
                                        <input type="number" step="0.01" min="0" max="{{ $sale->total_amount - $sale->amount_paid }}" 
                                            name="amount_paid" id="amount_paid" 
                                            value="{{ $sale->payment_status == 'partial' ? $sale->total_amount - $sale->amount_paid : '' }}" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Enter amount">
                                        <p class="mt-1 text-xs text-gray-500">Remaining: Rs. {{ number_format($sale->total_amount - $sale->amount_paid, 2) }}</p>
                                    </div>
                                </div>
                                
                                <!-- Payment Method -->
                                <div class="mt-4">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                    <select name="payment_method" id="payment_method" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="credit_card" {{ $sale->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="debit_card" {{ $sale->payment_method == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                        <option value="transfer" {{ $sale->payment_method == 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="btn btn-primary">Update Payment</button>
                                </div>
                            </form>
                        </div>

                        @push('js')
                        <script>
                            document.getElementById('payment_status').addEventListener('change', function() {
                                const amountField = document.getElementById('amountPaidField');
                                if (this.value === 'partial') {
                                    amountField.style.display = 'block';
                                } else {
                                    amountField.style.display = 'none';
                                }
                            });
                        </script>
                        @endpush
                        @endif


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