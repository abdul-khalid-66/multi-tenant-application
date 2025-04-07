<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-gray-700 mb-2">Select Sale</label>
        <select name="sale_id" id="sale-select" class="w-full rounded border-gray-300" required>
            <option value="">Select a sale</option>
            @foreach($sales as $sale)
                <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
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
        <textarea name="reason" class="w-full rounded border-gray-300" rows="3" required>{{ old('reason') }}</textarea>
    </div>
</div>

<div id="sale-items-container" class="{{ old('sale_id') ? '' : 'hidden' }}">
    <h3 class="text-lg font-medium mb-4">Select Items to Return</h3>
    <div id="items-list" class="space-y-4">
        @if(old('sale_id'))
            @php $sale = \App\Models\Sale::find(old('sale_id')); @endphp
            @foreach($sale->saleDetails as $detail)
                <div class="border rounded p-4">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <input type="checkbox" name="items[{{ $detail->id }}][include]" 
                                   id="item-{{ $detail->id }}" class="item-checkbox" 
                                   {{ in_array($detail->id, array_keys(old('items', []))) ? 'checked' : '' }}>
                            <label for="item-{{ $detail->id }}" class="font-medium ml-2">
                                {{ $detail->product->name }} {{ $detail->variant?->name ? '('.$detail->variant->name.')' : '' }}
                            </label>
                        </div>
                        <span>Available: {{ $detail->quantity - $detail->returned_quantity }}</span>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 item-details {{ in_array($detail->id, array_keys(old('items', []))) ? '' : 'hidden' }}">
                        <input type="hidden" name="items[{{ $detail->id }}][sale_detail_id]" value="{{ $detail->id }}">
                        
                        <div>
                            <label class="block text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="items[{{ $detail->id }}][quantity]" 
                                   min="1" max="{{ $detail->quantity - $detail->returned_quantity }}" 
                                   class="w-full rounded border-gray-300" 
                                   value="{{ old('items.'.$detail->id.'.quantity', 1) }}">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-1">Unit Price</label>
                            <input type="text" class="w-full rounded border-gray-300 bg-gray-100" 
                                   value="{{ number_format($detail->sell_price, 2) }}" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-1">Refund Amount</label>
                            <input type="text" class="w-full rounded border-gray-300 bg-gray-100" 
                                   value="{{ number_format($detail->sell_price, 2) }}" readonly>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('sale-select').addEventListener('change', function() {
    const saleId = this.value;
    if (!saleId) {
        document.getElementById('sale-items-container').classList.add('hidden');
        return;
    }
    
    fetch(`/sales/${saleId}/items`)
        .then(response => response.json())
        .then(data => {
            const itemsList = document.getElementById('items-list');
            itemsList.innerHTML = '';
            
            data.items.forEach(item => {
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
                        <span>Available: ${item.remaining_quantity}</span>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 item-details hidden">
                        <input type="hidden" name="items[${item.id}][sale_detail_id]" value="${item.id}">
                        
                        <div>
                            <label class="block text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="items[${item.id}][quantity]" 
                                   min="1" max="${item.remaining_quantity}" 
                                   class="w-full rounded border-gray-300" value="1">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-1">Unit Price</label>
                            <input type="text" class="w-full rounded border-gray-300 bg-gray-100" 
                                   value="${item.sell_price.toFixed(2)}" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-1">Refund Amount</label>
                            <input type="text" class="w-full rounded border-gray-300 bg-gray-100" 
                                   value="${item.sell_price.toFixed(2)}" readonly>
                        </div>
                    </div>
                </div>
                `;
                itemsList.insertAdjacentHTML('beforeend', itemHtml);
            });
            
            document.getElementById('sale-items-container').classList.remove('hidden');
            
            // Add checkbox event listeners
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    this.closest('.border').querySelector('.item-details')
                        .classList.toggle('hidden', !this.checked);
                });
            });
        });
});
</script>
@endpush