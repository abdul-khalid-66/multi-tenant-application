<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <!-- Main Content -->
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Customer Details</h2>
                            <div class="flex space-x-3">
                                <a href="{{ route('customers.edit', $customer->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </a>
                                <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $customer->name }}</h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $customer->contact }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                                <dl class="sm:divide-y sm:divide-gray-200">
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $customer->address ?? 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Total Purchases</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $customer->sales_count }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Total Returns</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $customer->returns_count }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $customer->created_at instanceof \Carbon\Carbon ? $customer->created_at->format('M d, Y H:i') : \Carbon\Carbon::parse($customer->created_at)->format('M d, Y H:i') }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-4">Recent Activity</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Recent Purchases -->
                                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                        <h3 class="text-base font-medium leading-6 text-gray-900">Recent Purchases</h3>
                                    </div>
                                    <div class="px-4 py-5 sm:p-0">
                                        @if($customer->sales->count() > 0)
                                            <ul class="divide-y divide-gray-200">
                                                @foreach($customer->sales as $sale)
                                                    <li class="py-4 px-4 sm:px-6">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">Invoice #{{ $sale->invoice_no }}</p>
                                                                <p class="text-sm text-gray-500">{{ $sale->date->format('M d, Y') }}</p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="text-sm font-medium text-gray-900">${{ number_format($sale->total_amount, 2) }}</p>
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                    {{ ucfirst($sale->payment_status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="px-4 py-4 text-center border-t border-gray-200">
                                                <a href="{{ route('sales.index', ['customer_id' => $customer->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                                    View all purchases
                                                </a>
                                            </div>
                                        @else
                                            <div class="px-4 py-5 text-center">
                                                <p class="text-sm text-gray-500">No purchase history found.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Recent Returns -->
                                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                        <h3 class="text-base font-medium leading-6 text-gray-900">Recent Returns</h3>
                                    </div>
                                    <div class="px-4 py-5 sm:p-0">
                                        @if($customer->returns->count() > 0)
                                            <ul class="divide-y divide-gray-200">
                                                @foreach($customer->returns as $return)
                                                    <li class="py-4 px-4 sm:px-6">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">Return #{{ $return->id }}</p>
                                                                <p class="text-sm text-gray-500">{{ $return->return_date->format('M d, Y') }}</p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="text-sm font-medium text-gray-900">${{ number_format($return->total_refund_amount, 2) }}</p>
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $return->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                                       ($return->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                                    {{ ucfirst($return->status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="px-4 py-4 text-center border-t border-gray-200">
                                                <a href="{{ route('returns.index', ['customer_id' => $customer->id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                                    View all returns
                                                </a>
                                            </div>
                                        @else
                                            <div class="px-4 py-5 text-center">
                                                <p class="text-sm text-gray-500">No return history found.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>