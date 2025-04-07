<x-tenant-app-layout>
    @include('app.sales.sidebar')
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-2xl font-bold mb-6">Process Return #{{ $return->id }}</h2>
                        
                        <div class="mb-8">
                            @include('app.sales.returns._details', ['details' => $return->returnDetails])
                            
                            <div class="mt-4 border-t pt-4">
                                <div class="flex justify-end">
                                    <div class="w-64">
                                        <div class="flex justify-between font-bold text-lg">
                                            <span>Total Refund:</span>
                                            <span>{{ number_format($return->total_refund_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('returns.approve', $return) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-gray-700 mb-2">Action</label>
                                    <select name="status" class="w-full rounded border-gray-300" required>
                                        <option value="approved">Approve Return</option>
                                        <option value="rejected">Reject Return</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 mb-2">Notes</label>
                                    <textarea name="notes" class="w-full rounded border-gray-300" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Submit Decision
                                </button>
                                <a href="{{ route('returns.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>