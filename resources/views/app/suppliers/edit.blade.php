<x-tenant-app-layout>
    @include('app.suppliers.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Edit Supplier: {{ $supplier->name }}</h2>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                                Back to Suppliers
                            </a>
                        </div>

                        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Supplier Name *</label>
                                    <input type="text" name="name" id="name" 
                                           value="{{ old('name', $supplier->name) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="contact" class="block text-sm font-medium text-gray-700">Contact Information *</label>
                                    <input type="text" name="contact" id="contact" 
                                           value="{{ old('contact', $supplier->contact) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           required>
                                    @error('contact')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4 md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                                    <textarea name="address" id="address" rows="3" 
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                              required>{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6 space-x-4">
                                <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Update Supplier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>