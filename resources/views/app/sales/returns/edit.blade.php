<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Return Request #{{ $return->id }}</h2>
                        
                        <form method="POST" action="{{ route('returns.update', $return) }}" id="returnForm">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Customer</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded border border-gray-200">
                                        {{ $return->customer->name }} ({{ $return->customer->contact }})
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sale Invoice</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded border border-gray-200">
                                        #{{ $return->sale->invoice_no }} - {{ $return->sale->date->format('M d, Y') }}
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Reason*</label>
                                    <select name="reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        @foreach(App\Models\ProductReturn::REASONS as $key => $reason)
                                            <option value="{{ $key }}" {{ $return->reason === $key ? 'selected' : '' }}>{{ $reason }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-800 mb-3">Items to Return</h3>
                                
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sold Qty</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Qty</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <!-- In your edit.blade.php -->
                                            @foreach($return->returnDetails as $detail)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $detail->product->name }}
                                                    <input type="hidden" name="items[{{ $detail->id }}][id]" value="{{ $detail->id }}">
                                                    <input type="hidden" name="items[{{ $detail->id }}][product_id]" value="{{ $detail->product_id }}">
                                                    <input type="hidden" name="items[{{ $detail->id }}][variant_id]" value="{{ $detail->variant_id }}">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $detail->variant->name ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $saleDetail = $detail->saleDetail();
                                                    @endphp
                                                    {{ $saleDetail ? $saleDetail->quantity : 0 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="number" name="items[{{ $detail->id }}][quantity]" 
                                                        min="1" 
                                                        max="{{ ($saleDetail = $detail->saleDetail()) ? ($saleDetail->quantity - $detail->getPreviouslyReturned()) : 0 }}"
                                                        value="{{ $detail->quantity_returned }}"
                                                        class="w-20 rounded border-gray-300 quantity-input"
                                                        onchange="updateRowTotal(this)">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    Rs.{{ number_format($detail->refund_amount_per_unit, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap row-total">
                                                    Rs.{{ number_format($detail->total_refund_amount, 2) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $return->notes }}</textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('returns.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Update Return Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function updateRowTotal(input) {
            const row = input.closest('tr');
            const unitPrice = parseFloat(row.querySelector('td:nth-child(5)').textContent.replace('Rs.', ''));
            const quantity = parseInt(input.value);
            const total = (unitPrice * quantity).toFixed(2);
            row.querySelector('.row-total').textContent = `Rs.${total}`;
        }
    </script>
    @endpush
</x-tenant-app-layout>