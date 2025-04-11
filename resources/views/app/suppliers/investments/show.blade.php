<x-tenant-app-layout>
    @include('app.suppliers.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Investment Details</h2>
                        <div>
                            <a href="{{ route('investments.edit', $investment) }}" class="btn-secondary mr-2">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <a href="{{ route('investments.index') }}" class="btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                                <div class="mt-2 border-t border-gray-200 pt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Amount:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->amount }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Type:</span>
                                        <span class="text-sm text-gray-900">{{ ucfirst($investment->type) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Date:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->date->format('d M Y') }}</span>
                                    </div>
                                    @if($investment->supplier)
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Supplier:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->supplier->name }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Description</h3>
                                <div class="mt-2 border-t border-gray-200 pt-2">
                                    <p class="text-sm text-gray-700">{{ $investment->description ?? 'No description provided.' }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">System Information</h3>
                                <div class="mt-2 border-t border-gray-200 pt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Created At:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Updated At:</span>
                                        <span class="text-sm text-gray-900">{{ $investment->updated_at->format('d M Y H:i') }}</span>
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






