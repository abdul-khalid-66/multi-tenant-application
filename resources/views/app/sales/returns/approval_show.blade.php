<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Return Request #{{ $return->id }}</h2>
                                <div class="flex items-center mt-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($return->status) }}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-500">
                                        Created on {{ $return->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('returns.pending') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                                    Back to List
                                </a>
                                @if($return->status === 'pending')
                                <a href="{{ route('returns.approve', $return) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                    Approve
                                </a>
                                <a href="{{ route('returns.reject', $return) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                    Reject
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Customer Information</h3>
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600">{{ $return->customer->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $return->customer->contact }}</p>
                                    <p class="text-sm text-gray-600">{{ $return->customer->address }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Sale Information</h3>
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600">
                                        Invoice: <a href="{{ route('sales.show', $return->sale_id) }}" class="text-blue-600 hover:underline">{{ $return->sale->invoice_no }}</a>
                                    </p>
                                    <p class="text-sm text-gray-600">Date: {{ $return->sale->date->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-600">Amount: {{ number_format($return->sale->total_amount, 2) }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Return Summary</h3>
                                <div class="space-y-1">
                                    <p class="text-sm text-gray-600">Date: {{ $return->return_date->format('M d, Y') }}</p>
                                    <p class="text-sm text-gray-600">Reason: {{ $return->reason }}</p>
                                    <p class="text-sm text-gray-600">Total Refund: {{ number_format($return->total_refund_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Items to Return</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($return->returnDetails as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $detail->product->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $detail->variant->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $detail->quantity_returned }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($detail->refund_amount_per_unit, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($detail->total_refund_amount, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($return->notes)
                        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Notes</h3>
                                    <div class="mt-1 text-sm text-blue-700">
                                        <p>{{ $return->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>