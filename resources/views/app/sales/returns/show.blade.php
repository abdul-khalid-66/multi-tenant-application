<x-tenant-app-layout>
    @include('app.sales.sidebar')
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Return #{{ $return->id }}</h2>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                {{ $return->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($return->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($return->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <h3 class="text-lg font-medium mb-2">Customer Information</h3>
                                <p><strong>Name:</strong> {{ $return->customer->name }}</p>
                                <p><strong>Contact:</strong> {{ $return->customer->contact }}</p>
                                <p><strong>Address:</strong> {{ $return->customer->address }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium mb-2">Return Information</h3>
                                <p><strong>Original Invoice:</strong> {{ $return->sale->invoice_no }}</p>
                                <p><strong>Return Date:</strong> {{ $return->return_date->format('M d, Y') }}</p>
                                <p><strong>Reason:</strong> {{ $return->reason }}</p>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium mb-4">Returned Items</h3>
                        @include('app.sales.returns._details', ['details' => $return->returnDetails])
                        
                        <div class="mt-6 border-t pt-4">
                            <div class="flex justify-end">
                                <div class="w-64">
                                    <div class="flex justify-between mb-2">
                                        <span class="font-medium">Subtotal:</span>
                                        <span>{{ number_format($return->total_refund_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>Total Refund:</span>
                                        <span>{{ number_format($return->total_refund_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('returns.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                Back to Returns
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>