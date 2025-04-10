<x-tenant-app-layout>
    @include('app.product.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold mb-6">Edit Category</h2>
                            <a href="{{ route('categories.index') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Back
                            </a>
                        </div>
                        <form action="{{ route('categories.update', $category->id )}}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6 mb-8">
                                <div class="bg-gray-50 p-6 rounded-lg shadow">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Category Name *</label>
                                        <input type="text" name="category_name" value="{{ $category->category_name }}" required class="mt-1 block w-full border rounded-md py-2 px-3">
                                    </div>
                            
                                    @if($category->subcategory)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                                        <input type="text" value="{{ $category->subcategory }}" class="mt-1 block w-full border rounded-md py-2 px-3 bg-gray-100" readonly>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                    Reset
                                </button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                    Update Variant
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>