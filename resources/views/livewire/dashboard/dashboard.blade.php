<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
        @foreach ($stats as $stat)
            <div class="rounded-lg overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md bg-white border-t border-b border-l border-r {{ $stat['border_color'] }}">
                <div class="p-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="p-2 rounded-full bg-gray-50">
                                <i class="{{ $stat['icon'] }} {{ $stat['color'] }}"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-gray-600">{{ $stat['title'] }}</h3>
                            <div class="mt-2 text-2xl font-semibold text-gray-900">
                                {{ number_format($stat['value']) }}
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $stat['info'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="h-0.5 w-full {{ str_replace('text-', 'bg-', $stat['color']) }} opacity-30"></div>
            </div>
        @endforeach
    </div>
</div>
