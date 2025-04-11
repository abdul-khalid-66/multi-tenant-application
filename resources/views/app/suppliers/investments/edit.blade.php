<x-tenant-app-layout>
    @include('app.suppliers.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Edit Investment</h2>
                        <a href="{{ route('investments.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i> Back to List
                        </a>
                    </div>

                    <form action="{{ route('investments.update', $investment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('app.suppliers.investments.form', [
                            'investment' => $investment,
                            'suppliers' => $suppliers
                        ])
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Update Investment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>