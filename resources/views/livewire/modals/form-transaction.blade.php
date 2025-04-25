<!-- Modal para crear/editar transacciones -->

<flux:modal wire:model="open" name="store-user" class="md:w-200">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ $selected_id ? 'Edit Transaction' : 'Create Transaction' }}
            </flux:heading>
        </div>

        <flux:select label="Usuarios" wire:model="user_id">
            <flux:select.option>Select a user</flux:select.option>
            @if(Auth::user()->hasRole('Admin'))
                @foreach(\App\Models\User::all() as $user)
                    <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
                @endforeach
            @else
                <flux:select.option value="{{ Auth::id() }}">{{ Auth::user()->name }}</flux:select.option>
            @endif
        </flux:select>

        <flux:select label="Type" wire:model="type">
            <flux:select.option>Seleciona un Rol</flux:select.option>
            <flux:select.option value="credit">Credito</flux:select.option>
            <flux:select.option value="debit">Debito</flux:select.option>
        </flux:select>

        <flux:input label="Amount" wire:model="amount" type="number" step="0.01" placeholder="type your amount" />

        <flux:textarea label="Description" rows="3"  wire:model="description" placeholder="write your description" />

        <flux:input label="transaction Date" type="date" wire:model="transaction_date" placeholder="Type de date" />

        <div class="flex">
            <flux:spacer />

            <flux:button type="submit"  wire:click="$set('open', false)">Cancel</flux:button>
            <flux:button type="submit" variant="primary" class="ml-2" wire:click.prevent="save">
                {{ $selected_id ? 'Update' : 'Create' }}
            </flux:button>
        </div>
    </div>
</flux:modal>






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
