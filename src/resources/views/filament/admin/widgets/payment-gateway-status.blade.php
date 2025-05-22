<x-filament::section>
    <x-slot name="heading">
        Payment Gateway Status
    </x-slot>

    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($gateways as $gateway)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 h-2 w-full 
                        @if($gateway['status_color'] === 'success') bg-success-500 
                        @elseif($gateway['status_color'] === 'warning') bg-warning-500 
                        @elseif($gateway['status_color'] === 'danger') bg-danger-500 
                        @else bg-gray-500 @endif">
                    </div>
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $gateway['name'] }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($gateway['provider']) }}</p>
                        </div>
                        
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($gateway['status_color'] === 'success') bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200
                                @elseif($gateway['status_color'] === 'warning') bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200
                                @elseif($gateway['status_color'] === 'danger') bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                {{ $gateway['status_text'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $gateway['is_active'] ? 'Active' : 'Inactive' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Latency</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $gateway['is_active'] ? $gateway['latency'] . 'ms' : '-' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Last checked: {{ $gateway['last_checked'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament::section>
