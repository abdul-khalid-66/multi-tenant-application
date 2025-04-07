<x-tenant-app-layout>
    @include('app.sales.sidebar')
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold mb-6">Create Return Request</h1>
                        
                        <form action="{{ route('returns.store') }}" method="POST">
                            @csrf
                            
                            <div class="bg-white rounded-lg shadow p-6 mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-700 mb-2">Select Sale</label>
                                        <select name="sale_id" id="sale-select" class="w-full rounded border-gray-300" required>
                                            <option value="">Select a sale</option>
                                            @foreach($sales as $sale)
                                                <option value="{{ $sale->id }}">
                                                    Invoice #{{ $sale->invoice_no }} - {{ $sale->customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-gray-700 mb-2">Return Date</label>
                                        <input type="date" name="return_date" class="w-full rounded border-gray-300" 
                                                value="{{ old('return_date', now()->format('Y-m-d')) }}" required>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-gray-700 mb-2">Reason</label>
                                        <textarea name="reason" class="w-full rounded border-gray-300" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="sale-items-container" class="hidden">
                                <div class="bg-white rounded-lg shadow p-6 mb-6">
                                    <h2 class="text-xl font-semibold mb-4">Select Items to Return</h2>
                                    <div id="items-list" class="space-y-4"></div>
                                </div>
                                
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Submit Return Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @routes --}}
    @push('js')     

    <script>
        document.getElementById('sale-select').addEventListener('change', function() {
        const saleId = this.value;
        if (!saleId) {
            document.getElementById('sale-items-container').classList.add('hidden');
            return;
        }
        
        // fetch(route('sales.items', { sale: saleId }))
        fetch(`/app/sales/${saleId}/items`)
            .then(response => response.json())
            .then(data => {
                const itemsList = document.getElementById('items-list');
                itemsList.innerHTML = '';
                
                data.items.forEach(item => {
                    // Calculate refund per unit (price + tax - discount)
                    const refundPerUnit = (item.sell_price + item.tax_per_unit - item.discount_per_unit).toFixed(2);
                    
                    const itemHtml = `
                    <div class="border rounded p-4">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <input type="checkbox" name="items[${item.id}][include]" 
                                    id="item-${item.id}" class="item-checkbox">
                                <label for="item-${item.id}" class="font-medium ml-2">
                                    ${item.product_name} ${item.variant_name ? '('+item.variant_name+')' : ''}
                                </label>
                            </div>
                            <span>Available: ${item.remaining_quantity} (of ${item.quantity})</span>
                        </div>
                        
                        <div class="grid grid-cols-4 gap-4 item-details hidden">
                            <input type="hidden" name="items[${item.id}][sale_detail_id]" value="${item.id}">
                            
                            <div>
                                <label class="block text-gray-700 mb-1">Quantity</label>
                                <input type="number" name="items[${item.id}][quantity]" 
                                    min="1" max="${item.remaining_quantity}" 
                                    class="w-full rounded border-gray-300 quantity-input" 
                                    value="1" data-unit-price="${refundPerUnit}">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-1">Unit Price</label>
                                <input type="text" class="w-full rounded border-gray-300 bg-gray-100" 
                                    value="${item.sell_price.toFixed(2)}" readonly>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-1">Refund/Unit</label>
                                <input type="text" class="w-full rounded border-gray-300 bg-gray-100 refund-per-unit" 
                                    value="${refundPerUnit}" readonly>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-1">Total Refund</label>
                                <input type="text" name="items[${item.id}][total_refund]" 
                                    class="w-full rounded border-gray-300 bg-gray-100 total-refund" 
                                    value="${refundPerUnit}" readonly>
                            </div>
                        </div>
                    </div>
                    `;
                    itemsList.insertAdjacentHTML('beforeend', itemHtml);
                });
                
                document.getElementById('sale-items-container').classList.remove('hidden');
                
                // Add event listeners
                document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        this.closest('.border').querySelector('.item-details')
                            .classList.toggle('hidden', !this.checked);
                    });
                });
                
                // Add quantity change listeners
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const quantity = parseInt(this.value);
                        const max = parseInt(this.max);
                        const unitPrice = parseFloat(this.dataset.unitPrice);
                        
                        // Validate quantity
                        if (quantity > max) {
                            this.value = max;
                            return;
                        }
                        if (quantity < 1) {
                            this.value = 1;
                            return;
                        }
                        
                        // Calculate total refund
                        const totalRefund = (quantity * unitPrice).toFixed(2);
                        this.closest('.item-details').querySelector('.total-refund').value = totalRefund;
                    });
                });
            });
        });
    </script>
    @endpush
</x-tenant-app-layout>



