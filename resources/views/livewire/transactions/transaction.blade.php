<div>
    <div class="flex justify-between items-center mb-4">
        <!-- Breadcrumb -->
        <nav class="text-gray-600 text-sm flex items-center">
            <a href="#" class="hover:text-gray-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="#" class="hover:text-gray-600">Transactions</a>
        </nav>

        <!-- Controles de usuario (Búsqueda + Fechas + Botón) -->
        <div class="flex items-center space-x-4">
            <input
                type="text"
                icon="magnifying-glass"
                kbd="⌘K"
                wire:model.live.debounce.500ms="search"
                placeholder="Search..."
                class="border rounded-lg px-4 py-2 text-sm w-64 focus:ring focus:ring-gray-300"
            >
            <div class="flex items-center space-x-2">
                <input
                    type="date"
                    wire:model.live="start_date"
                    class="border rounded-lg px-4 py-2 text-sm focus:ring focus:ring-gray-300"
                >
                <span class="text-gray-500">a</span>
                <input
                    type="date"
                    wire:model.live="end_date"
                    class="border rounded-lg px-4 py-2 text-sm focus:ring focus:ring-gray-300"
                >
                <button
                    wire:click="clearFilters"
                    class="text-gray-500 hover:text-gray-700"
                    title="Limpiar filtros"
                >
                    <flux:icon name="x-circle" class="h-5 w-5" />
                </button>
            </div>
            <flux:modal.trigger wire:model="open" name="store-user">
                <flux:button variant="primary">
                    + Create a new transaction.
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <div class="mt-2 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden ring-1 shadow-sm ring-black/5 sm:rounded-lg">
                    @if ($transactions->count())
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    <button wire:click="sortBy('name')" class="flex items-center gap-2">
                                        <div>Name</div>
                                        @if($sortColumn === 'name')
                                            <div class="text-gray-400">
                                                @if($sortDirection === 'asc')
                                                    <flux:icon name="chevron-up" class="h-4 w-4 text-gray-500" />
                                                @else
                                                    <flux:icon name="chevron-down" class="h-4 w-4 text-gray-500" />
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-gray-400">
                                                <flux:icon name="chevron-up-down" class="h-4 w-4 text-gray-500" />
                                            </div>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    <button wire:click="sortBy('type')" class="flex items-center gap-2">
                                        <div>Type</div>
                                        @if($sortColumn === 'type')
                                            <div class="text-gray-400">
                                                @if($sortDirection === 'asc')
                                                    <flux:icon name="chevron-up" class="h-4 w-4 text-gray-500" />
                                                @else
                                                    <flux:icon name="chevron-down" class="h-4 w-4 text-gray-500" />
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-gray-400">
                                                <flux:icon name="chevron-up-down" class="h-4 w-4 text-gray-500" />
                                            </div>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    <button wire:click="sortBy('transaction_date')" class="flex items-center gap-2">
                                        <div>Date</div>
                                        @if($sortColumn === 'transaction_date')
                                            <div class="text-gray-400">
                                                @if($sortDirection === 'asc')
                                                    <flux:icon name="chevron-up" class="h-4 w-4 text-gray-500" />
                                                @else
                                                    <flux:icon name="chevron-down" class="h-4 w-4 text-gray-500" />
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-gray-400">
                                                <flux:icon name="chevron-up-down" class="h-4 w-4 text-gray-500" />
                                            </div>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td class="py-2 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6">
                                        {{$transaction->user->name}}
                                    </td>
                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">
                                        @if($transaction->type == 'credit')
                                            <flux:badge color="blue" icon="credit-card" size="sm">Credit</flux:badge>
                                        @else
                                            <flux:badge color="yellow" icon="credit-card" size="sm">Debit</flux:badge>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">
                                        $ {{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">
                                        {{ $transaction->description }}
                                    </td>
                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="flex align-middle justify-center relative py-2 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-6">
                                        <flux:button.group>
                                            <flux:button class="hover:text-yellow-600" tooltip="Editar"  wire:click="edit({{ $transaction->id }})"><i class="fa-thin fa-edit"></i></flux:button>
                                            @if($transaction->deleted_at == null)
                                                <flux:button class="hover:text-red-600" tooltip="Desactivar" wire:click.prevent="deleteConfimation({{ $transaction->id }})"><i class="fa-thin fa-times"></i></flux:button>
                                            @endif
                                        </flux:button.group>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-4">
                            No existen registros que mostrar
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <!-- Componente de notificación -->
{{--    <livewire:components.notification />--}}
    @include('livewire.modals.form-transaction')
    <!-- Modal de confirmación -->
    <livewire:modals.confirm-modal />

    <div class="pt-4 flex justify-between items-center">
        <div class="text-gray-700 text-sm">
            Resultado: {{ \Illuminate\Support\Number::format($transactions->total()) }}
        </div>
        {{ $transactions->links('livewire.pagination.pagination') }}
    </div>
</div>
