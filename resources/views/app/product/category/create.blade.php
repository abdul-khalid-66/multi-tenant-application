<x-tenant-app-layout>
    @include('app.product.sidebar')

    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-bold mb-6">{{ isset($category) ? 'Edit' : 'Create' }} Category</h2>
                        
                        <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
                            @csrf
                            @if(isset($category))
                                @method('PUT')
                            @endif

                            <div class="grid grid-cols-1 gap-6 mb-8">
                                <div class="bg-gray-50 p-6 rounded-lg shadow">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Category Name *</label>
                                        <input type="text" name="category_name" required class="mt-1 block w-full border rounded-md py-2 px-3">
                                    </div>
                            
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_subcategory" class="form-checkbox">
                                            <span class="ml-2">Is Subcategory?</span>
                                        </label>
                                    </div>
                            
                                    <div class="mb-4" id="parentCategoryContainer" style="display:none">
                                        <label class="block text-sm font-medium text-gray-700">Parent Category</label>
                                        <select name="parent_category" class="mt-1 block w-full border rounded-md py-2 px-3">
                                            @foreach($mainCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($category) ? 'Update' : 'Save' }} Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
    <script>
        document.querySelector('[name="is_subcategory"]').addEventListener('change', function() {
            document.getElementById('parentCategoryContainer').style.display = this.checked ? 'block' : 'none';
        });
    </script>
    @endpush
</x-tenant-app-layout>