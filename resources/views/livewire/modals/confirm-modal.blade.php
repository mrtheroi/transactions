<div>
    @if($show)
        <div x-data="{ show: @entangle('show'), confirmation: '' }" x-show="show" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
                    <!-- Header -->
                    <div class="flex items-center gap-3 px-6 py-4 ">
                        <div class="bg-red-100 rounded-full p-2">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Eliminar registro</h2>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-5 space-y-4">
                        <p class="text-sm text-gray-600">
                            ¿Estás seguro de querer eliminar este registro de forma permanente? Esta acción no se puede deshacer.
                        </p>

                        <flux:input x-model="confirmation" label="Escribe 'CONFIRM'" />
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 px-6 py-4 ">
                        <button @click="show = false"
                                class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border rounded-md hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button wire:click="confirmDelete"
                                :disabled="confirmation !== 'CONFIRM'"
                                :class="confirmation === 'CONFIRM' ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-300 cursor-not-allowed'"
                                class="px-4 py-2 text-sm font-semibold text-white rounded-md">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
