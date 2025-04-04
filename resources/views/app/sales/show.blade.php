<x-tenant-app-layout>
    @include('app.sales.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Sale Invoice #{{ $sale->invoice_no }}</h2>
                            <div class="flex space-x-2">
                                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                    Back to Sales
                                </a>
                                <button onclick="window.print()" class="btn btn-primary">
                                    Print Invoice
                                </button>
                            </div>
                        </div>

                        <!-- Invoice Header -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <h3 class="text-lg font-medium mb-2">From</h3>
                                <p class="text-gray-700">
                                    <strong>Your Business Name</strong><br>
                                    123 Business Street<br>
                                    City, State 10001<br>
                                    Phone: (123) 456-7890<br>
                                    Email: info@yourbusiness.com
                                </p>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-2">To</h3>
                                <p class="text-gray-700">
                                    <strong>{{ $sale->customer->name }}</strong><br>
                                    {{ $sale->customer->address }}<br>
                                    Phone: {{ $sale->customer->contact }}<br>
                                    Email: {{ $sale->customer->email ??