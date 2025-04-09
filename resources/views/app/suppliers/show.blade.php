<x-tenant-app-layout>
    @include('app.suppliers.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Supplier Details</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
                                Edit
                            </a>
                            <a href="{{ route('suppliers.performance', $supplier) }}" class="btn btn-info">
                                Performance
                            </a>
                            <a href="{{ route('suppliers.products', $supplier) }}" class="btn btn-primary">
                                View Products
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                            <div class="space-y-2">
                                <p><strong>Name:</strong> {{ $supplier->name }}</p>
                                <p><strong>Contact:</strong> {{ $supplier->contact }}</p>
                                <p><strong>Address:</strong> {{ $supplier->address }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Quick Stats</h3>
                            <div class="space-y-2">
                                <p><strong>Products Supplied:</strong> {{ $supplier->products()->count() }}</p>
                                <p><strong>Inventory Changes:</strong> {{ $supplier->inventoryLogs()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-tenant-app-layout>