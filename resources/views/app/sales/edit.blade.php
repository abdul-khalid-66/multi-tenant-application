<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Edit Sale #{{ $sale->invoice_no }}</h2>
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                Back to Sales
                            </a>
                        </div>

                        <form id="saleForm" action="{{ route('sales.update', $sale->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Customer Information -->
                                <div class="col-span-2 md:col-span-1">
                                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                                    <select name="customer_id" id="customer_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->contact }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sale Information -->
                                <div class="col-span-2 md:col-span-1">
                                    <label for="date" class="block text-sm font-medium text-gray-700">Date *</label>
                                    <input type="datetime-local" name="date" id="date" value="{{ old('date', $sale->date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>

                                <!-- Payment Information -->
                                <div>
                                    <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status *</label>
                                    <select name="payment_status" id="payment_status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="paid" {{ $sale->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="pending" {{ $sale->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="partial" {{ $sale->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                    <select name="payment_method" id="payment_method" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="credit_card" {{ $sale->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="debit_card" {{ $sale->payment_method == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                        <option value="transfer" {{ $sale->payment_method == 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                </div>

                                <!-- Discount & Tax -->
                                <div>
                                    <label for="discount" class="block text-sm font-medium text-gray-700">Discount (Rs)</label>
                                    <input type="number" step="0.01" min="0" name="discount" id="discount" value="{{ old('discount', $sale->discount) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="tax" class="block text-sm font-medium text-gray-700">Tax (Rs)</label>
                                    <input type="number" step="0.01" min="0" name="tax" id="tax" value="{{ old('tax', $sale->tax) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <!-- Notes -->
                                <div class="col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('notes', $sale->notes) }}</textarea>
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Sale Items</h3>
                                    <button type="button" id="addItemBtn" class="btn btn-primary">
                                        Add Item
                                    </button>
                                </div>

                                <div id="itemsContainer" class="space-y-4">
                                    <!-- Existing items will be added here dynamically -->
                                </div>

                                <!-- Totals Section -->
                                <div class="mt-6 border-t pt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-start-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="font-medium">Subtotal:</span>
                                                <span id="subtotal">Rs {{ number_format($sale->total_amount + $sale->discount - $sale->tax, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-medium">Discount:</span>
                                                <span id="discountDisplay">Rs {{ number_format($sale->discount, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-medium">Tax:</span>
                                                <span id="taxDisplay">Rs {{ number_format($sale->tax, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                                <span>Total:</span>
                                                <span id="total">Rs {{ number_format($sale->total_amount, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Update Sale</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Define products as a JavaScript variable by directly embedding the JSON object
            const products = @json($products);
            const saleItems = @json($sale->saleDetails);

            // Add item button functionality
            let itemCount = 0;
            const itemsContainer = document.getElementById('itemsContainer');
            const addItemBtn = document.getElementById('addItemBtn');

            addItemBtn.addEventListener('click', function() {
                addItemRow();
            });

            function addItemRow(item = null) {
                itemCount++;
                const itemId = 'item_' + itemCount;
                
                const itemRow = document.createElement('div');
                itemRow.className = 'item-row bg-gray-50 p-4 rounded-md';
                itemRow.dataset.id = itemId;
                itemRow.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Product Select -->
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Product *</label>
                            <select name="items[${itemCount}][product_id]" class="product-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="">Select Product</option>
                                ${products.map(product => `
                                    <option value="${product.id}" ${item && item.product_id == product.id ? 'selected' : ''}>
                                        ${product.name}
                                    </option>
                                `).join('')}
                            </select>
                        </div>

                        <!-- Variant Select -->
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Variant</label>
                            <select name="items[${itemCount}][variant_id]" class="variant-select mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">No Variant</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                            <input type="number" name="items[${itemCount}][quantity]" min="1" value="${item ? item.quantity : 1}" class="quantity mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>

                        <!-- Price -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Price *</label>
                            <input type="number" step="0.01" min="0" name="items[${itemCount}][sell_price]" value="${item ? item.sell_price : ''}" class="price mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>

                        <!-- Remove Button -->
                        <div class="md:col-span-1 flex items-end">
                            <button type="button" class="remove-item-btn btn btn-danger w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Additional Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-2">
                        <!-- Unit -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Unit</label>
                            <input type="text" name="items[${itemCount}][unit]" value="${item ? item.unit : 'pcs'}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Note -->
                        <div class="md:col-span-10">
                            <label class="block text-sm font-medium text-gray-700">Note</label>
                            <input type="text" name="items[${itemCount}][note]" value="${item ? item.note : ''}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                `;

                itemsContainer.appendChild(itemRow);

                // Initialize variant select based on selected product
                const productSelect = itemRow.querySelector('.product-select');
                const variantSelect = itemRow.querySelector('.variant-select');
                const priceInput = itemRow.querySelector('.price');

                // Update variants when product changes
                productSelect.addEventListener('change', function() {
                    const productId = this.value;
                    const product = products.find(p => p.id == productId);
                    
                    // Clear and repopulate variants
                    variantSelect.innerHTML = '<option value="">No Variant</option>';
                    
                    if (product && product.variants.length > 0) {
                        product.variants.forEach(variant => {
                            const option = document.createElement('option');
                            option.value = variant.id;
                            option.textContent = variant.name;
                            variantSelect.appendChild(option);
                        });
                    }

                    // Set default price
                    if (product) {
                        priceInput.value = product.price.toFixed(2);
                    }
                });

                // Update price when variant changes
                variantSelect.addEventListener('change', function() {
                    const productId = productSelect.value;
                    const variantId = this.value;
                    const product = products.find(p => p.id == productId);
                    
                    if (variantId && product) {
                        const variant = product.variants.find(v => v.id == variantId);
                        if (variant) {
                            priceInput.value = variant.price.toFixed(2);
                        }
                    } else if (product) {
                        priceInput.value = product.price.toFixed(2);
                    }
                });

                // Remove item button
                itemRow.querySelector('.remove-item-btn').addEventListener('click', function() {
                    itemRow.remove();
                    calculateTotals();
                });

                // Trigger change event if item is provided
                if (item) {
                    productSelect.dispatchEvent(new Event('change'));
                    if (item.variant_id) {
                        setTimeout(() => {
                            variantSelect.value = item.variant_id;
                            variantSelect.dispatchEvent(new Event('change'));
                        }, 100);
                    }
                }

                // Add event listeners for quantity and price changes
                itemRow.querySelector('.quantity').addEventListener('input', calculateTotals);
                itemRow.querySelector('.price').addEventListener('input', calculateTotals);

                return itemRow;
            }

            // Calculate totals
            function calculateTotals() {
                let subtotal = 0;
                
                document.querySelectorAll('.item-row').forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.price').value) || 0;
                    subtotal += quantity * price;
                });

                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const tax = parseFloat(document.getElementById('tax').value) || 0;
                const total = subtotal - discount + tax;

                // Update display
                document.getElementById('subtotal').textContent = 'Rs' + subtotal.toFixed(2);
                document.getElementById('discountDisplay').textContent = 'Rs' + discount.toFixed(2);
                document.getElementById('taxDisplay').textContent = 'Rs' + tax.toFixed(2);
                document.getElementById('total').textContent = 'Rs' + total.toFixed(2);
            }

            // Add event listeners for discount and tax
            document.getElementById('discount').addEventListener('input', calculateTotals);
            document.getElementById('tax').addEventListener('input', calculateTotals);

            // Add existing items
            saleItems.forEach(item => {
                addItemRow(item);
            });

            // Calculate initial totals
            calculateTotals();
        });
    </script>
    @endpush
</x-tenant-app-layout>