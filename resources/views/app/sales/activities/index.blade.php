<x-tenant-app-layout>
    @include('app.sales.sidebar')
    
    <div class="content-area" id="contentArea">
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-900">All Activities</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($activities as $activity)
                        <div class="flex items-start p-4 border border-gray-100 rounded-lg hover:bg-gray-50">
                            <div class="p-2 rounded-full bg-{{ $activity->color }}-100 text-{{ $activity->color }}-600 mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($activity->icon === 'check')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    @elseif($activity->icon === 'plus')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @elseif($activity->icon === 'x')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    @elseif($activity->icon === 'pencil')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <p class="font-medium text-gray-900">{{ $activity->title }}</p>
                                    <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500 mt-2">By: {{ $activity->user }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>