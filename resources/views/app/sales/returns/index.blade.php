<x-tenant-app-layout>
    @include('app.sales.sidebar')
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Return Requests</h2>
                            <a href="{{ route('returns.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Create Return
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($returns as $return)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">#{{ $return->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $return->sale->invoice_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $return->customer->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($return->total_refund_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $return->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                   ($return->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($return->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('returns.show', $return) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            @if($return->status === 'pending')
                                                <a href="{{ route('returns.edit', $return) }}" class="text-indigo-600 hover:text-indigo-900">Process</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $returns->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>