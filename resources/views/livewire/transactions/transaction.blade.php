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
            <flux:button variant="primary" wire:click="create">
                + Create a new transaction.
            </flux:button>
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
{{--                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">--}}
{{--                                        <div class="flex space-x-2">--}}
{{--                                            <button wire:click="edit({{ $transaction->id }})" class="text-blue-500 hover:text-blue-700">--}}
{{--                                                <flux:icon name="pencil" class="h-4 w-4" />--}}
{{--                                            </button>--}}
{{--                                            <button wire:click="deleteConfirmation({{ $transaction->id }})" class="text-red-500 hover:text-red-700">--}}
{{--                                                <flux:icon name="trash" class="h-4 w-4" />--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
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

    <!-- Modal para crear/editar transacciones -->
{{--    <flux:modal wire:model="open">--}}
{{--        <flux:slot name="title">--}}
{{--            {{ $selected_id ? 'Edit Transaction' : 'Create Transaction' }}--}}
{{--        </flux:slot>--}}

{{--        <flux:slot name="content">--}}
{{--            <form wire:submit.prevent="save">--}}
{{--                <div class="space-y-4">--}}
{{--                    <!-- User ID -->--}}
{{--                    <div>--}}
{{--                        <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>--}}
{{--                        <select id="user_id" wire:model="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">--}}
{{--                            @if(Auth::user()->hasRole('Admin'))--}}
{{--                                @foreach(\App\Models\User::all() as $user)--}}
{{--                                    <option value="{{ $user->id }}">{{ $user->name }}</option>--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                                <option value="{{ Auth::id() }}">{{ Auth::user()->name }}</option>--}}
{{--                            @endif--}}
{{--                        </select>--}}
{{--                        @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror--}}
{{--                    </div>--}}

{{--                    <!-- Type -->--}}
{{--                    <div>--}}
{{--                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>--}}
{{--                        <select id="type" wire:model="type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">--}}
{{--                            <option value="">Select type</option>--}}
{{--                            <option value="credit">Credit</option>--}}
{{--                            <option value="debit">Debit</option>--}}
{{--                        </select>--}}
{{--                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror--}}
{{--                    </div>--}}

{{--                    <!-- Amount -->--}}
{{--                    <div>--}}
{{--                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>--}}
{{--                        <input type="number" step="0.01" id="amount" wire:model="amount" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">--}}
{{--                        @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror--}}
{{--                    </div>--}}

{{--                    <!-- Description -->--}}
{{--                    <div>--}}
{{--                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>--}}
{{--                        <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>--}}
{{--                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror--}}
{{--                    </div>--}}

{{--                    <!-- Transaction Date -->--}}
{{--                    <div>--}}
{{--                        <label for="transaction_date" class="block text-sm font-medium text-gray-700">Transaction Date</label>--}}
{{--                        <input type="date" id="transaction_date" wire:model="transaction_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">--}}
{{--                        @error('transaction_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </flux:slot>--}}

{{--        <flux:slot name="footer">--}}
{{--            <div class="flex justify-end space-x-3">--}}
{{--                <flux:button variant="secondary" wire:click="$set('open', false)">--}}
{{--                    Cancel--}}
{{--                </flux:button>--}}
{{--                <flux:button variant="primary" wire:click="save">--}}
{{--                    {{ $selected_id ? 'Update' : 'Create' }}--}}
{{--                </flux:button>--}}
{{--            </div>--}}
{{--        </flux:slot>--}}
{{--    </flux:modal>--}}

    <!-- Componente de notificación -->
{{--    <livewire:components.notification />--}}

    <!-- Modal de confirmación -->
    <livewire:modals.confirm-modal />

    <div class="pt-4 flex justify-between items-center">
        <div class="text-gray-700 text-sm">
            Resultado: {{ \Illuminate\Support\Number::format($transactions->total()) }}
        </div>
        {{ $transactions->links('livewire.pagination.pagination') }}
    </div>
</div>
