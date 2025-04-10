<x-tenant-app-layout>
    @include('app.product.sidebar')

    <!-- Main Content -->
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Product Management</h2>
                            <a href="{{ route('products.create') }}" class="sidebar-item {{ request()->routeIs('products.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-square icon"></i>
                                <span class="text">Add Product</span>
                            </a>
                        </div>

                        <!-- Search and Filter Bar -->
                        <div class="mb-6 flex flex-col md:flex-row gap-4">
                            <div class="relative flex-grow">
                                <input type="text" id="searchInput" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <select id="statusFilter" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Statuses</option>
                                <option value="available">Available</option>
                                <option value="out of stock">Out of Stock</option>
                            </select>
                            <select id="categoryFilter" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }} - {{ $category->subcategory }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Products Table -->
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="name">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="category">
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Variants
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="status">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="supplier">
                                            Supplier
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                    <tr class="hover:bg-gray-50" data-category-id="{{ $product->category->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-md object-cover" 
                                                    src="{{ $product->image ? asset($product->image) : asset('images/product.png') }}" 
                                                    alt="{{ $product->name ?? 'Product image' }}">                                                
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $product->category->category_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $product->category->subcategory }}</div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                                @foreach($product->variants as $variant)
                                                <div class="relative group">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-800 border border-blue-100 hover:bg-blue-100 transition-colors">
                                                        {{ $variant->name }} ({{ $variant->stock_quantity }})
                                                    </span>
                                                    <div class="absolute z-10 hidden group-hover:block bg-white p-2 rounded-md shadow-lg border border-gray-200 text-xs mt-1">
                                                        <div class="whitespace-nowrap">SKU: {{ $variant->sku ?? 'N/A' }}</div>
                                                        <div class="whitespace-nowrap">Price: ${{ number_format($variant->price_sale, 2) }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->status == 'available')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Available
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Out of Stock
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $product->variants->sum('stock_quantity') }} in stock
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $product->supplier->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <a href="{{ route('products.show', $product->id) }}" class="text-gray-600 hover:text-gray-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();
                const categoryValue = categoryFilter.value;
                
                document.querySelectorAll('tbody tr').forEach(row => {
                    const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const category = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const categoryId = row.getAttribute('data-category-id');
                    
                    const nameMatch = name.includes(searchTerm);
                    const statusMatch = statusValue === '' || status.includes(statusValue);
                    const categoryMatch = categoryValue === '' || categoryId === categoryValue;
                    
                    if (nameMatch && statusMatch && categoryMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            searchInput.addEventListener('input', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            categoryFilter.addEventListener('change', applyFilters);
            
            // Sort functionality
            document.querySelectorAll('.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.getAttribute('data-column');
                    // Implement sorting logic here (would typically be server-side)
                    console.log('Sort by:', column);
                });
            });
        });
    </script>
    @endpush
</x-tenant-app-layout>