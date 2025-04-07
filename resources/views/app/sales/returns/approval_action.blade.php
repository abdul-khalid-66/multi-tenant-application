<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $title }} - Return #{{ $return->id }}</h2>

                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Customer</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $return->customer->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Invoice</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $return->sale->invoice_no }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Return Date</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $return->return_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Refund</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ number_format($return->total_refund_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('returns.process', $return) }}">
                            @csrf
                            <input type="hidden" name="action" value="{{ $action }}">

                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $action === 'approve' ? 'Approval Notes' : 'Rejection Reason' }}
                                </label>
                                <textarea id="notes" name="notes" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $action === 'approve' ? 'Optional notes about this approval' : 'Please specify the reason for rejection' }}
                                </p>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('returns.show', $return) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-{{ $action === 'approve' ? 'green' : 'red' }}-600 text-white px-4 py-2 rounded hover:bg-{{ $action === 'approve' ? 'green' : 'red' }}-700">
                                    {{ ucfirst($action) }} Return
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>