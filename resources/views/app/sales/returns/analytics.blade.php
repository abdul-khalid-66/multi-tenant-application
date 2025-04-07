<x-tenant-app-layout>
    @include('app.sales.sidebar')
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold mb-6">Return Reason Analytics</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="text-lg font-medium mb-4">Returns by Reason</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <canvas id="reasonChart" height="300"></canvas>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium mb-4">Refund Amount by Reason</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <canvas id="amountChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Refund</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($analytics as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->return_reason }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->total_refund, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format(($item->count / $analytics->sum('count')) * 100, 1) }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reason Count Chart
            const reasonCtx = document.getElementById('reasonChart').getContext('2d');
            new Chart(reasonCtx, {
                type: 'bar',
                data: {
                    labels: @json($analytics->pluck('return_reason')),
                    datasets: [{
                        label: 'Number of Returns',
                        data: @json($analytics->pluck('count')),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Refund Amount Chart
            const amountCtx = document.getElementById('amountChart').getContext('2d');
            new Chart(amountCtx, {
                type: 'pie',
                data: {
                    labels: @json($analytics->pluck('return_reason')),
                    datasets: [{
                        label: 'Refund Amount',
                        data: @json($analytics->pluck('total_refund')),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(139, 92, 246, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
    </script>
    @endpush
</x-tenant-app-layout>