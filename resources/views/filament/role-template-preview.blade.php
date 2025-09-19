<div class="space-y-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <h3 class="font-semibold text-lg text-gray-900">{{ $preview['template'] }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ $preview['description'] }}</p>
        <div class="mt-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $preview['permissions_count'] }} permissions included
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @php
            $permissionsByCategory = collect($preview['permissions'])->groupBy('category');
        @endphp

        @foreach($permissionsByCategory as $category => $permissions)
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    {{ $category }}
                </h4>
                
                <div class="space-y-2">
                    @foreach($permissions as $permission)
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucwords(str_replace(['-', '.'], [' ', ' '], $permission['name'])) }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $permission['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-yellow-800">Before you create this role:</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>This will create a new role in your workspace with all the permissions listed above. You can always modify the permissions later if needed.</p>
                </div>
            </div>
        </div>
    </div>
</div>