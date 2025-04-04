<x-tenant-app-layout>
    @include('app.product.sidebar')

    <!-- Main Content -->
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Inventory Logs</h2>
                        </div>

                        <!-- Filter Bar -->
                        <div class="mb-6 flex flex-col md:flex-row gap-4">
                            <div class="relative flex-grow">
                                <input type="text" id="searchInput" placeholder="Search logs..." class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <select id="productFilter" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="variantFilter" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Variants</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}" {{ request('variant_id') == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="date" id="dateFrom" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="From">
                            <input type="date" id="dateTo" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="To">
                        </div>

                        <!-- Logs Table -->
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="date">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="product">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="variant">
                                            Variant
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Old Stock
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            New Stock
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Difference
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="reason">
                                            Reason
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($logs as $log)
                                    <tr class="hover:bg-gray-50" data-product-id="{{ $log->product_id }}" data-variant-id="{{ $log->variant_id }}" data-date="{{ $log->date instanceof \Carbon\Carbon ? $log->date->format('Y-m-d') : \Carbon\Carbon::parse($log->date)->format('Y-m-d') }}" data-reason="{{ $log->reason }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->date instanceof \Carbon\Carbon ? $log->date->format('Y-m-d H:i') : \Carbon\Carbon::parse($log->date)->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $log->product->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $log->variant?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->old_stock }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->new_stock }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="{{ $log->new_stock - $log->old_stock >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $log->new_stock - $log->old_stock }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $log->reason === 'restock' ? 'bg-green-100 text-green-800' : 
                                                   ($log->reason === 'sale' ? 'bg-blue-100 text-blue-800' : 
                                                   'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($log->reason) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $logs->appends(request()->query())->links() }}
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
            const productFilter = document.getElementById('productFilter');
            const variantFilter = document.getElementById('variantFilter');
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const productValue = productFilter.value;
                const variantValue = variantFilter.value;
                const dateFromValue = dateFrom.value;
                const dateToValue = dateTo.value;
                
                document.querySelectorAll('tbody tr').forEach(row => {
                    const product = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const variant = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const reason = row.getAttribute('data-reason').toLowerCase();
                    const productId = row.getAttribute('data-product-id');
                    const variantId = row.getAttribute('data-variant-id');
                    const logDate = row.getAttribute('data-date');
                    
                    const textMatch = product.includes(searchTerm) || 
                                     variant.includes(searchTerm) || 
                                     reason.includes(searchTerm);
                    const productMatch = productValue === '' || productId === productValue;
                    const variantMatch = variantValue === '' || variantId === variantValue;
                    const dateFromMatch = dateFromValue === '' || logDate >= dateFromValue;
                    const dateToMatch = dateToValue === '' || logDate <= dateToValue;
                    
                    if (textMatch && productMatch && variantMatch && dateFromMatch && dateToMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            searchInput.addEventListener('input', applyFilters);
            productFilter.addEventListener('change', applyFilters);
            variantFilter.addEventListener('change', applyFilters);
            dateFrom.addEventListener('change', applyFilters);
            dateTo.addEventListener('change', applyFilters);
            
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