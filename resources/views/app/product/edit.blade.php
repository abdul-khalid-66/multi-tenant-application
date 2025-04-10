<x-tenant-app-layout>
    @include('app.product.sidebar')

    <!-- Main Content -->
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Edit Product: {{ $product->name }}</h2>
                            <a href="{{ route('products.index') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Back
                            </a>
                        </div>

                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Basic Information -->
                                <div class="bg-gray-50 p-6 rounded-lg shadow">
                                    <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                                    
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                                        <div class="mt-1 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <img id="imagePreview" class="h-24 w-24 rounded-md object-cover bg-gray-100" src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/150' }}" alt="Product preview">
                                            </div>
                                            <div class="flex-grow w-full">
                                                <input 
                                                    type="file" 
                                                    name="image" 
                                                    id="image" 
                                                    accept="image/*"
                                                    class="block w-full text-sm text-gray-500
                                                           file:mr-4 file:py-2 file:px-4
                                                           file:rounded-md file:border-0
                                                           file:text-sm file:font-semibold
                                                           file:bg-blue-50 file:text-blue-700
                                                           hover:file:bg-blue-100
                                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                    onchange="previewImage(this)"
                                                >
                                                @error('image')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                        <select name="status" id="status" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="out of stock" {{ old('status', $product->status) == 'out of stock' ? 'selected' : '' }}>Out of Stock</option>
                                        </select>
                                        @error('status')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Category & Supplier -->
                                <div class="bg-gray-50 p-6 rounded-lg shadow">
                                    <h3 class="text-lg font-medium mb-4">Category & Supplier</h3>
                                    
                                    <div class="mb-4">
                                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                        <select name="category_id" id="category_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select a category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->category_name }} - {{ $category->subcategory }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier *</label>
                                        <select name="supplier_id" id="supplier_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Select a supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="reorder_level" class="block text-sm font-medium text-gray-700">Reorder Level</label>
                                        <input type="number" name="reorder_level" id="reorder_level" min="0" value="{{ old('reorder_level', $product->reorder_level) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">Minimum stock level to trigger restock alerts</p>
                                        @error('reorder_level')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Product Variants -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow mb-8">
                                <h3 class="text-lg font-medium mb-4">Product Variants</h3>
                                <p class="text-sm text-gray-500 mb-4">Manage variants for your product (e.g., sizes, colors).</p>
                                
                                <div id="variantsContainer">
                                    @foreach($product->variants as $index => $variant)
                                    <div class="variant-item border-b border-gray-200 pb-4 mb-4">
                                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label for="variants[{{ $index }}][name]" class="block text-sm font-medium text-gray-700">Variant Name *</label>
                                                <input type="text" name="variants[{{ $index }}][name]" value="{{ old("variants.$index.name", $variant->name) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[{{ $index }}][sku]" class="block text-sm font-medium text-gray-700">SKU *</label>
                                                <input type="text" name="variants[{{ $index }}][sku]" value="{{ old("variants.$index.sku", $variant->sku) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[{{ $index }}][price_sale]" class="block text-sm font-medium text-gray-700">Sale Price *</label>
                                                <input type="number" step="0.01" min="0" name="variants[{{ $index }}][price_sale]" value="{{ old("variants.$index.price_sale", $variant->price_sale) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[{{ $index }}][price_cost]" class="block text-sm font-medium text-gray-700">Cost Price *</label>
                                                <input type="number" step="0.01" min="0" name="variants[{{ $index }}][price_cost]" value="{{ old("variants.$index.price_cost", $variant->price_cost) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                            <div>
                                                <label for="variants[{{ $index }}][stock_quantity]" class="block text-sm font-medium text-gray-700">Stock Quantity *</label>
                                                <input type="number" min="0" name="variants[{{ $index }}][stock_quantity]" value="{{ old("variants.$index.stock_quantity", $variant->stock_quantity) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[{{ $index }}][weight]" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                                <input type="number" step="0.01" min="0" name="variants[{{ $index }}][weight]" value="{{ old("variants.$index.weight", $variant->weight) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[{{ $index }}][status]" class="block text-sm font-medium text-gray-700">Status *</label>
                                                <select name="variants[{{ $index }}][status]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="available" {{ old("variants.$index.status", $variant->status) == 'available' ? 'selected' : '' }}>Available</option>
                                                    <option value="out of stock" {{ old("variants.$index.status", $variant->status) == 'out of stock' ? 'selected' : '' }}>Out of Stock</option>
                                                </select>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removeVariant(this)" class="text-red-600 hover:text-red-900 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" onclick="addVariant()" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add Another Variant
                                </button>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end">
                                <a href="{{ route('products.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Update Product
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
        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const file = input.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/150' }}";
            }
        }

        // Variant management
        let variantCount = {{ count($product->variants) }};

        function addVariant() {
            const container = document.getElementById('variantsContainer');
            const newVariant = document.createElement('div');
            newVariant.className = 'variant-item border-b border-gray-200 pb-4 mb-4';
            newVariant.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="variants[${variantCount}][name]" class="block text-sm font-medium text-gray-700">Variant Name *</label>
                        <input type="text" name="variants[${variantCount}][name]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="variants[${variantCount}][sku]" class="block text-sm font-medium text-gray-700">SKU *</label>
                        <input type="text" name="variants[${variantCount}][sku]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="variants[${variantCount}][price_sale]" class="block text-sm font-medium text-gray-700">Sale Price *</label>
                        <input type="number" step="0.01" min="0" name="variants[${variantCount}][price_sale]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="variants[${variantCount}][price_cost]" class="block text-sm font-medium text-gray-700">Cost Price *</label>
                        <input type="number" step="0.01" min="0" name="variants[${variantCount}][price_cost]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label for="variants[${variantCount}][stock_quantity]" class="block text-sm font-medium text-gray-700">Stock Quantity *</label>
                        <input type="number" min="0" name="variants[${variantCount}][stock_quantity]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="variants[${variantCount}][weight]" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                        <input type="number" step="0.01" min="0" name="variants[${variantCount}][weight]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="variants[${variantCount}][status]" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="variants[${variantCount}][status]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="available">Available</option>
                            <option value="out of stock">Out of Stock</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeVariant(this)" class="text-red-600 hover:text-red-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Remove
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newVariant);
            variantCount++;
        }

        function removeVariant(button) {
            const variants = document.querySelectorAll('.variant-item');
            if (variants.length > 1) {
                button.closest('.variant-item').remove();
            } else {
                alert('You must have at least one variant.');
            }
        }
    </script>
    @endpush
</x-tenant-app-layout>