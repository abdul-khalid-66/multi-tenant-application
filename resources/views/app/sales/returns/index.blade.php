<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Return Requests</h2>
                            <a href="{{ route('returns.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Return Request
                            </a>
                        </div>

                        <!-- Filters Section -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Status Filter -->
                                <div>
                                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select id="status-filter" class="filter-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">All Statuses</option>
                                        @foreach(['pending', 'approved', 'rejected', 'processed'] as $status)
                                            <option value="{{ $status }}">
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Date Range Filter -->
                                <div>
                                    <label for="start-date-filter" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                                    <input type="date" id="start-date-filter" 
                                        class="filter-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="end-date-filter" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                                    <input type="date" id="end-date-filter" 
                                        class="filter-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <!-- Customer Filter -->
                                <div>
                                    <label for="customer-filter" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                                    <select id="customer-filter" class="filter-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">All Customers</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end space-x-3">
                                <button id="reset-filters" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                                    Reset
                                </button>
                            </div>
                        </div>

                        <!-- Status Tabs -->
                        <div class="mb-4 flex space-x-2">
                            <button data-status="" class="status-tab px-3 py-1 rounded bg-blue-100 text-blue-800">
                                All Returns
                            </button>
                            <button data-status="pending" class="status-tab px-3 py-1 rounded bg-gray-100 text-gray-800">
                                Pending Approval
                            </button>
                            <button data-status="approved" class="status-tab px-3 py-1 rounded bg-gray-100 text-gray-800">
                                Approved
                            </button>
                            <button data-status="rejected" class="status-tab px-3 py-1 rounded bg-gray-100 text-gray-800">
                                Rejected
                            </button>
                        </div>

                        <!-- Results Count -->
                        <div class="mb-4 text-sm text-gray-600">
                            Showing <span id="showing-count">{{ $returns->count() }}</span> of <span id="total-count">{{ $returns->total() }}</span> results
                        </div>

                        <!-- Returns Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="returns-table-body" class="bg-white divide-y divide-gray-200">
                                    @foreach($returns as $return)
                                    <tr class="return-row" 
                                        data-status="{{ $return->status }}"
                                        data-customer-id="{{ $return->customer_id }}"
                                        data-date="{{ $return->return_date->format('Y-m-d') }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $return->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $return->customer->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a href="{{ route('sales.show', $return->sale_id) }}" class="text-blue-600 hover:underline">
                                                {{ $return->sale->invoice_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($return->total_refund_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                    'processed' => 'bg-blue-100 text-blue-800'
                                                ];
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$return->status] }}">
                                                {{ ucfirst($return->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $return->return_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('returns.show', $return) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            @if($return->status === 'pending')
                                                <a href="{{ route('returns.edit', $return) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination will need to be handled differently for client-side filtering -->
                        <div class="mt-4">
                            {{ $returns->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all filter elements
            const statusFilter = document.getElementById('status-filter');
            const startDateFilter = document.getElementById('start-date-filter');
            const endDateFilter = document.getElementById('end-date-filter');
            const customerFilter = document.getElementById('customer-filter');
            const resetButton = document.getElementById('reset-filters');
            const statusTabs = document.querySelectorAll('.status-tab');
            const showingCount = document.getElementById('showing-count');
            const totalCount = document.getElementById('total-count');
            
            // Add event listeners to all filters
            [statusFilter, startDateFilter, endDateFilter, customerFilter].forEach(filter => {
                filter.addEventListener('change', applyFilters);
            });
            
            // Add event listeners to status tabs
            statusTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab styling
                    statusTabs.forEach(t => t.classList.remove('bg-blue-100', 'text-blue-800'));
                    statusTabs.forEach(t => t.classList.add('bg-gray-100', 'text-gray-800'));
                    this.classList.remove('bg-gray-100', 'text-gray-800');
                    this.classList.add('bg-blue-100', 'text-blue-800');
                    
                    // Set status filter value
                    statusFilter.value = this.dataset.status;
                    applyFilters();
                });
            });
            
            // Reset button
            resetButton.addEventListener('click', function() {
                // Reset all filters
                statusFilter.value = '';
                startDateFilter.value = '';
                endDateFilter.value = '';
                customerFilter.value = '';
                
                // Reset active tab to "All Returns"
                statusTabs.forEach(t => t.classList.remove('bg-blue-100', 'text-blue-800'));
                statusTabs.forEach(t => t.classList.add('bg-gray-100', 'text-gray-800'));
                statusTabs[0].classList.remove('bg-gray-100', 'text-gray-800');
                statusTabs[0].classList.add('bg-blue-100', 'text-blue-800');
                
                applyFilters();
            });
            
            function applyFilters() {
                const statusValue = statusFilter.value;
                const startDateValue = startDateFilter.value;
                const endDateValue = endDateFilter.value;
                const customerValue = customerFilter.value;
                
                const rows = document.querySelectorAll('.return-row');
                let visibleCount = 0;
                
                rows.forEach(row => {
                    const rowStatus = row.dataset.status;
                    const rowCustomerId = row.dataset.customerId;
                    const rowDate = row.dataset.date;
                    
                    // Check if row matches all filters
                    const statusMatch = !statusValue || rowStatus === statusValue;
                    const customerMatch = !customerValue || rowCustomerId === customerValue;
                    const dateMatch = (!startDateValue || rowDate >= startDateValue) && 
                                     (!endDateValue || rowDate <= endDateValue);
                    
                    if (statusMatch && customerMatch && dateMatch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update showing count
                showingCount.textContent = visibleCount;
            }
            
            // Initialize with all rows visible
            showingCount.textContent = document.querySelectorAll('.return-row').length;
        });
    </script>
    @endpush
</x-tenant-app-layout>