<x-tenant-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Quick Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Revenue Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Revenue</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($todayRevenue, 2) }}</p>
                            <div class="flex items-center text-sm mt-1">
                                <span class="text-{{ $revenueChange >= 0 ? 'green' : 'red' }}-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $revenueChange >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path>
                                    </svg>
                                    {{ abs($revenueChange) }}%
                                </span>
                                <span class="text-gray-500 ml-2 dark:text-gray-400">vs yesterday</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{-- route('sales.index') --}}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        View sales report
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Profit Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Profit</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($todayProfit, 2) }}</p>
                            <div class="flex items-center text-sm mt-1">
                                <span class="text-{{ $profitChange >= 0 ? 'green' : 'red' }}-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $profitChange >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path>
                                    </svg>
                                    {{ abs($profitChange) }}%
                                </span>
                                <span class="text-gray-500 ml-2 dark:text-gray-400">vs last week</span>
                            </div>
                            <!-- Change the profit card text to show daily comparison -->
                            {{-- <div class="flex items-center text-sm mt-1">
                                <span class="text-{{ $profitChange >= 0 ? 'green' : 'red' }}-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $profitChange >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path>
                                    </svg>
                                    {{ abs($profitChange) }}%
                                </span>
                                <span class="text-gray-500 ml-2 dark:text-gray-400">vs yesterday</span>
                            </div> --}}
                        </div>
                        <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{-- route('reports.financial') --}}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        View financial details
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Inventory Status -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Inventory Status</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalProducts }} items</p>
                            <div class="flex items-center text-sm mt-1">
                                <span class="text-red-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                    {{ $lowStockItems }} low stock
                                </span>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{-- route('inventory.index') --}}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Manage inventory
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Pending Tasks -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Tasks</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingTasks }} items</p>
                            <div class="flex items-center text-sm mt-1">
                                <span class="text-yellow-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ min($pendingTasks, 3) }} urgent
                                </span>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="{{-- route('tasks.index') --}}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        View all tasks
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Sales Chart -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Performance</h3>
                        <div class="flex space-x-2">
                            <button onclick="updateChart('week')" class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-md dark:bg-blue-900/30 dark:text-blue-400">Week</button>
                            <button onclick="updateChart('month')" class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-md dark:text-gray-400 dark:hover:bg-gray-700">Month</button>
                            <button onclick="updateChart('year')" class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-md dark:text-gray-400 dark:hover:bg-gray-700">Year</button>
                        </div>
                    </div>
                    <div class="h-64">
                        @push('js')
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            let salesChart;
                            document.addEventListener('DOMContentLoaded', function() {
                                const ctx = document.getElementById('salesChart').getContext('2d');
                                salesChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: @json(array_column($salesData, 'date')),
                                        datasets: [{
                                            label: 'Sales (Rs)',
                                            data: @json(array_column($salesData, 'total')),
                                            backgroundColor: 'rgba(59, 130, 246, 0.05)',
                                            borderColor: 'rgba(59, 130, 246, 0.8)',
                                            borderWidth: 2,
                                            tension: 0.4,
                                            fill: true,
                                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                                            pointRadius: 4,
                                            pointHoverRadius: 6
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        return '$' + context.raw.toLocaleString();
                                                    }
                                                }
                                            },
                                            legend: {
                                                display: false
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function(value) {
                                                        return 'Rs' + value.toLocaleString();
                                                    }
                                                },
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.05)'
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                
                            function updateChart(period) {
                                // Show loading state
                                document.getElementById('salesChart').style.opacity = '0.5';
                                
                                // Get CSRF token
                                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                
                                // Fetch new data
                                fetch('/dashboard/sales-data', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token
                                    },
                                    body: JSON.stringify({period: period})
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // Update chart data
                                    salesChart.data.labels = data.labels;
                                    salesChart.data.datasets[0].data = data.values;
                                    salesChart.update();
                                    
                                    // Remove loading state
                                    document.getElementById('salesChart').style.opacity = '1';
                                    
                                    // Update button active states
                                    document.querySelectorAll('.period-button').forEach(btn => {
                                        btn.classList.remove('bg-blue-50', 'text-blue-600', 'dark:bg-blue-900/30', 'dark:text-blue-400');
                                        btn.classList.add('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-400', 'dark:hover:bg-gray-700');
                                    });
                                    
                                    const activeButton = document.querySelector(`button[onclick="updateChart('${period}')"]`);
                                    activeButton.classList.add('bg-blue-50', 'text-blue-600', 'dark:bg-blue-900/30', 'dark:text-blue-400');
                                    activeButton.classList.remove('text-gray-600', 'hover:bg-gray-100', 'dark:text-gray-400', 'dark:hover:bg-gray-700');
                                })
                                .catch(error => {
                                    console.error('Error fetching sales data:', error);
                                    document.getElementById('salesChart').style.opacity = '1';
                                });
                            }
                            updateChart('week')
                        </script>
                        @endpush
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activities</h3>
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start">
                            <div class="p-2 rounded-full bg-{{ $activity['iconColor'] }}-100 text-{{ $activity['iconColor'] }}-600 dark:bg-{{ $activity['iconColor'] }}-900/30 dark:text-{{ $activity['iconColor'] }}-400 mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($activity['icon'] === 'check')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    @elseif($activity['icon'] === 'plus')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @elseif($activity['icon'] === 'x')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    @elseif($activity['icon'] === 'pencil')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['title'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        View all activities
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Low Stock Items -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Low Stock Items</h3>
                    <div class="space-y-3">
                        @foreach($lowStockProducts as $product)
                        @php
                            $stockLevelClass = $product->stock_quantity < 3 ? 'red' : ($product->stock_quantity < 6 ? 'orange' : 'yellow');
                        @endphp
                        <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded dark:hover:bg-gray-700/50">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-{{ $stockLevelClass }}-100 text-{{ $stockLevelClass }}-600 dark:bg-{{ $stockLevelClass }}-900/30 dark:text-{{ $stockLevelClass }}-400 mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->product->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-{{ $stockLevelClass }}-600 dark:text-{{ $stockLevelClass }}-400">{{ $product->stock_quantity }} left</span>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('inventory.low-stock') }}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        View all low stock items
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Recent Sales -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Sales</h3>
                    <div class="space-y-3">
                        @foreach($recentSales as $sale)
                        <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded dark:hover:bg-gray-700/50">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">#{{ $sale->invoice_no }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->customer->name ?? 'Guest' }} • {{ $sale->saleDetails->count() }} items</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($sale->total_amount, 2) }}</p>
                                <p class="text-xs text-{{ $sale->payment_status === 'paid' ? 'green' : ($sale->payment_status === 'pending' ? 'yellow' : 'gray') }}-500">{{ ucfirst($sale->payment_status) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('sales.index') }}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        View all sales
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Pending Returns -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pending Returns</h3>
                    <div class="space-y-3">
                        @foreach($pendingReturnsList as $return)
                        @php
                            $statusColor = [
                                'pending' => 'red',
                                'processing' => 'yellow',
                                'approved' => 'blue',
                                'rejected' => 'gray',
                                'processed' => 'green'
                            ][$return->status] ?? 'gray';
                        @endphp
                        <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded dark:hover:bg-gray-700/50">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">#RET-{{ $return->id }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $return->reason }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($return->total_refund_amount, 2) }}</p>
                                <p class="text-xs text-{{ $statusColor }}-500">{{ ucfirst($return->status) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('returns.index') }}" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Manage returns
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
