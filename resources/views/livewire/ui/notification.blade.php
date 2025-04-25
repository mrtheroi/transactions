<div>
    @if($visible)
        <div x-data="{ show: true }"
             x-init="
                @if($autoClose)
                    setTimeout(() => { show = false }, {{ $duration }})
                @endif
             "
             x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-90 translate-y-5"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-90"
             @hidden.window="show = false"
             class="fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 pointer-events-none z-50"
             wire:key="notification-{{ now() }}">

            <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
                <div class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black/5">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="shrink-0">
                                @if($type == 'success')
                                    <svg class="size-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75" />
                                    </svg>
                                @elseif($type == 'error')
                                    <svg class="size-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5" />
                                    </svg>
                                @elseif($type == 'warning')
                                    <svg class="size-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 6.75h.008v.008H12v-.008z" />
                                    </svg>
                                @elseif($type == 'info')
                                    <svg class="size-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" fill="none" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                @if($title)
                                    <p class="text-sm font-medium text-gray-900">{{ $title }}</p>
                                @endif
                                <p class="text-sm text-gray-500">{{ $message }}</p>
                            </div>
                            <div class="ml-4 flex shrink-0">
                                <button type="button" @click="show = false"
                                        class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">
                                    <span class="sr-only">Close</span>
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
