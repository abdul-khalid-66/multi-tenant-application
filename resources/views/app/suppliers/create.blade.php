
<x-tenant-app-layout>
    @include('app.suppliers.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">Add New Supplier</h2>                    
                    <form method="POST" action="{{ route('suppliers.store') }}">
                        @csrf                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Supplier Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="contact" class="block text-sm font-medium text-gray-700">Contact Information</label>
                            <input type="text" name="contact" id="contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                Save Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>