<x-tenant-app-layout>
    @include('app.product.sidebar')

    <!-- Main Content -->
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Add New Product</h2>
                            <a href="{{ route('products.index') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Back
                            </a>
                        </div>

                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Basic Information -->
                                <div class="bg-gray-50 p-6 rounded-lg shadow">
                                    <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                                    
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                                        <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        @error('name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                        @error('description')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                                        <div class="mt-1 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <img id="imagePreview" class="h-24 w-24 rounded-md object-cover bg-gray-100" src="{{ asset('images/product.png') }}" alt="Product preview" onerror="this.src='/images/product.png'">
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
                                            <option value="available">Available</option>
                                            <option value="out of stock">Out of Stock</option>
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
                                                <option value="{{ $category->id }}">{{ $category->category_name }} - {{ $category->subcategory }}</option>
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
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="reorder_level" class="block text-sm font-medium text-gray-700">Reorder Level</label>
                                        <input type="number" name="reorder_level" id="reorder_level" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
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
                                <p class="text-sm text-gray-500 mb-4">Add at least one variant for your product (e.g., sizes, colors).</p>
                                
                                <div id="variantsContainer">
                                    <div class="variant-item border-b border-gray-200 pb-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label for="variants[0][name]" class="block text-sm font-medium text-gray-700">Variant Name *</label>
                                                <input type="text" name="variants[0][name]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[0][sku]" class="block text-sm font-medium text-gray-700">SKU *</label>
                                                <input type="text" name="variants[0][sku]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[0][price_sale]" class="block text-sm font-medium text-gray-700">Sale Price *</label>
                                                <input type="number" step="0.01" min="0" name="variants[0][price_sale]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[0][price_cost]" class="block text-sm font-medium text-gray-700">Cost Price *</label>
                                                <input type="number" step="0.01" min="0" name="variants[0][price_cost]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                            <div>
                                                <label for="variants[0][stock_quantity]" class="block text-sm font-medium text-gray-700">Stock Quantity *</label>
                                                <input type="number" min="0" name="variants[0][stock_quantity]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[0][weight]" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                                <input type="number" step="0.01" min="0" name="variants[0][weight]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label for="variants[0][status]" class="block text-sm font-medium text-gray-700">Status *</label>
                                                <select name="variants[0][status]" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
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
                                    </div>
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
                                <button type="reset" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                                    Reset
                                </button>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Save Product
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
                preview.src = "https://via.placeholder.com/150";
            }
        }

        // Variant management
        let variantCount = 1;

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