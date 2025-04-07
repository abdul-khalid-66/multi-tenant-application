<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Return Request</h2>
                        
                        <form method="POST" action="{{ route('returns.store') }}" id="returnForm">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Customer*</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded border border-gray-200" id="customer-info">
                                        Select a sale invoice to view customer
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sale Invoice*</label>
                                    @if($sales->isEmpty())
                                        <div class="mt-1 p-2 bg-yellow-50 border border-yellow-200 rounded-md text-yellow-700">
                                            No eligible sales found for return. All paid sales either already have return requests or have been fully returned.
                                        </div>
                                    @else
                                        <select name="sale_id" id="sale_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="">Select Sale Invoice</option>
                                            @foreach($sales as $sale)
                                                <option value="{{ $sale->id }}" 
                                                    data-customer="{{ $sale->customer->name }} ({{ $sale->customer->contact }})">
                                                    #{{ $sale->invoice_no }} - {{ $sale->customer->name }} ({{ $sale->date->format('M d, Y') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sale_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    @endif
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Reason*</label>
                                    <select name="reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Select Reason</option>
                                        @foreach(App\Models\ProductReturn::REASONS as $key => $reason)
                                            <option value="{{ $key }}">{{ $reason }}</option>
                                        @endforeach
                                    </select>
                                    @error('reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Per Item</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Per Item</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="saleItemsContainer" class="bg-white divide-y divide-gray-200">
                                            <tr id="noItemsMessage">
                                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                    Select a sale invoice to view items
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <a href="{{ route('returns.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Submit Return Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @routes
    @push('js')
    <script>
        // Build routes map
        const routeMap = {
            @foreach(\Illuminate\Support\Facades\Route::getRoutes()->get() as $route)
                @if ($route->getName()) // Avoid unnamed routes
                    '{{ $route->getName() }}': '{{ $route->uri() }}',
                @endif
            @endforeach
        };
    
        // Helper function to resolve route with parameters
        window.route = (name, params = {}) => {
            if (!routeMap[name]) {
                console.error(`Route "${name}" not found.`);
                return '#';
            }
            return Object.keys(params).reduce((url, key) => {
                return url.replace(`{${key}}`, params[key]);
            }, '/' + routeMap[name]); // Prefix with slash for proper path
        };
    </script>
    <script>
        document.getElementById('sale_id').addEventListener('change', function() {
            const saleId = this.value;
            const container = document.getElementById('saleItemsContainer');
            const noItemsMessage = document.getElementById('noItemsMessage');

            if (!saleId) {
                container.innerHTML = '';
                noItemsMessage.style.display = '';
                container.appendChild(noItemsMessage);
                return;
            }

            fetch(route('sales.items', { sale: saleId }), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                container.innerHTML = '';
                
                if (data.error) {
                    throw new Error(data.message);
                }

                const items = Array.isArray(data) ? data : [];
                
                if (items.length === 0) {
                    noItemsMessage.style.display = '';
                    container.appendChild(noItemsMessage);
                    return;
                }

                items.forEach(item => {
                    if (item.remaining_quantity > 0) { // Only show items with remaining quantity
                        const row = document.createElement('tr');
                        row.className = 'item-row';
                        
                        const unitPrice = parseFloat(item.sell_price) || 0;
                        const discountPerUnit = parseFloat(item.discount_per_unit) || 0;
                        const taxPerUnit = parseFloat(item.tax_per_unit) || 0;
                        const refundPerUnit = (unitPrice + taxPerUnit - discountPerUnit).toFixed(2);
                        
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${item.product_name || 'N/A'}
                                <input type="hidden" name="items[${item.id}][sale_detail_id]" value="${item.id}">
                                <input type="hidden" name="items[${item.id}][product_id]" value="${item.product_id}">
                                <input type="hidden" name="items[${item.id}][variant_id]" value="${item.variant_id || ''}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${item.variant_name || '-'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${item.quantity || 0}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" name="items[${item.id}][quantity]" 
                                    min="1" max="${item.remaining_quantity}" value="1" 
                                    class="w-20 rounded border-gray-300 quantity-input" 
                                    data-unit-price="${unitPrice}"
                                    data-discount="${discountPerUnit}"
                                    data-tax="${taxPerUnit}"
                                    onchange="updateRowTotal(this)">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap unit-price">
                                Rs.${unitPrice.toFixed(2)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap discount-per-unit">
                                Rs.${discountPerUnit.toFixed(2)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap tax-per-unit">
                                Rs.${taxPerUnit.toFixed(2)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap row-total">
                                Rs.${refundPerUnit}
                            </td>
                        `;
                        container.appendChild(row);
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            });
        });

        function updateRowTotal(input) {
            const quantity = parseInt(input.value);
            const max = parseInt(input.max);
            const unitPrice = parseFloat(input.dataset.unitPrice);
            const discountPerUnit = parseFloat(input.dataset.discount);
            const taxPerUnit = parseFloat(input.dataset.tax);
            const row = input.closest('tr');
            
            // Validate quantity
            if (quantity > max) {
                input.value = max;
                return;
            }
            if (quantity < 1) {
                input.value = 1;
                return;
            }
            
            // Calculate total refund (unit price + tax - discount) * quantity
            const totalRefund = ((unitPrice + taxPerUnit - discountPerUnit) * quantity).toFixed(2);
            row.querySelector('.row-total').textContent = `Rs.${totalRefund}`;
        }
    </script>
    @endpush
</x-tenant-app-layout>