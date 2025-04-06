<x-tenant-app-layout>


    @include('app.sales.sidebar')

    <!-- Main Content -->
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold">Invoice Management</h2>
                            <div class="flex space-x-2">
                                <form action="{{ route('invoices.search') }}" method="GET" class="flex">
                                    <input type="text" name="search" placeholder="Search invoices..." 
                                           class="px-4 py-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-500 text-white rounded-r hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Search
                                    </button>
                                </form>
                            </div>
                        </div>
        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->invoice_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->date->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->customer->name ?? 'Walk-in Customer' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ config('settings.currency_symbol') }}{{ number_format($invoice->total_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($invoice->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('invoices.show', $invoice->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('invoices.print', $invoice->id) }}" 
                                               class="text-gray-600 hover:text-gray-900 mr-3" target="_blank">Print</a>
                                            <a href="{{ route('invoices.pdf', $invoice->id) }}" 
                                               class="text-red-600 hover:text-red-900">PDF</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
        
                        <div class="mt-4">
                            {{ $invoices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
</x-tenant-app-layout>
<!-- resources/views/tenant/invoices/index.blade.php -->
{{-- <x-tenant-app-layout>

    @include('app.product.sidebar')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Invoice Management</h2>
                    <div class="flex space-x-2">
                        <form action="{{ route('invoices.search') }}" method="GET" class="flex">
                            <input type="text" name="search" placeholder="Search invoices..." 
                                   class="px-4 py-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-500 text-white rounded-r hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Search
                            </button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->invoice_no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->customer->name ?? 'Walk-in Customer' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ config('settings.currency_symbol') }}{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($invoice->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tenant.invoices.show', $invoice->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    <a href="{{ route('tenant.invoices.print', $invoice->id) }}" 
                                       class="text-gray-600 hover:text-gray-900 mr-3" target="_blank">Print</a>
                                    <a href="{{ route('tenant.invoices.pdf', $invoice->id) }}" 
                                       class="text-red-600 hover:text-red-900">PDF</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-tenant-app-layout> --}}