<x-tenant-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- {{ __("You're logged in!") }} --}}
                    @role('admin') 
                        {{-- <x-link-button href="{{ route('users.index') }}">Users</x-tenant-button> --}}


                            <div class="flex flex-wrap gap-1 justify-start">
                                <!-- Products Card -->
                                
                                <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Products</h5>
                                    <div class="mb-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-3xl font-semibold">142</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Total products</p>
                                        </div>
                                        <div class="text-green-500 bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    </div>
                                     <a href="{{-- {{ route('products.index') }} --}}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View all products 
                                        <svg class="w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                </div>
                            
                                <!-- Inventory Card -->
                                <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Inventory</h5>
                                    <div class="mb-3 space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Low stock items</span>
                                            <span class="font-semibold text-red-500">8</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Variants</span>
                                            <span class="font-semibold">326</span>
                                        </div>
                                    </div>
                                    <a href="{{-- {{ route('inventory.index') }} --}}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View inventory
                                        <svg class="w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                </div>
                            
                                <!-- Sales Card -->
                                <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Today's Sales</h5>
                                    <div class="mb-3">
                                        <p class="text-3xl font-semibold">$3,845</p>
                                        <div class="flex items-center text-sm text-green-500 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                            <span>12% from yesterday</span>
                                        </div>
                                    </div>
                                    <a href="{{-- {{ route('sales.index') }} --}}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View sales
                                        <svg class="w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                </div>
                            
                                <!-- Financials Card -->
                                <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Financials</h5>
                                    <div class="mb-3 space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Profit</span>
                                            <span class="font-semibold text-green-500">$2,150</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Expenses</span>
                                            <span class="font-semibold text-red-500">$895</span>
                                        </div>
                                    </div>
                                    <a href="{{-- {{ route('reports.financial') }} --}}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View reports
                                        <svg class="w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                </div>
                                <!-- Financials Card -->
                                <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Financials</h5>
                                    <div class="mb-3 space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Profit</span>
                                            <span class="font-semibold text-green-500">$2,150</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Expenses</span>
                                            <span class="font-semibold text-red-500">$895</span>
                                        </div>
                                    </div>
                                    <a href="{{-- {{ route('reports.financial') }} --}}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View reports
                                        <svg class="w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            

                            
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
