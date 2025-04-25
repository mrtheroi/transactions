<div>
    <div class="flex justify-between items-center mb-4">
        <!-- Breadcrumb -->
        <nav class="text-gray-600 text-sm flex items-center">
            <a href="#" class="hover:text-gray-600">Dashboard</a>
            <span class="mx-2">/</span>
            <a href="#" class="hover:text-gray-600">Usuarios</a>
        </nav>

        <!-- Controles de usuario (Búsqueda + Botón) -->
        <div class="flex items-center space-x-4">
            <input
                type="text"
                icon="magnifying-glass"
                kbd="⌘K"
                wire:model.live.debounce.500ms="search"
                placeholder="Buscar usuario..."
                class="border rounded-lg px-4 py-2 text-sm w-64 focus:ring focus:ring-gray-300"
            >
            <flux:modal.trigger wire:model="open" name="store-user">
                <flux:button variant="primary">
                    + Crear Usuario
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <div class="mt-2 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden ring-1 shadow-sm ring-black/5 sm:rounded-lg">
                    @if ($users->count())
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    <button wire:click="sortBy('name')" class="flex items-center gap-2">
                                        <div>Nombre</div>
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
                                    <button wire:click="sortBy('email')" class="flex items-center gap-2">
                                        <div>Email</div>
                                        @if($sortColumn === 'email')
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
                                    <button wire:click="sortBy('status')" class="flex items-center gap-2">
                                        <div>Estatus</div>
                                        @if($sortColumn === 'status')
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
                                    <button wire:click="sortBy('role')" class="flex items-center gap-2">
                                        <div>Rol</div>
                                        @if($sortColumn === 'role')
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
                            @foreach($users as $user)
                                <tr>
                                    <td class="py-2 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6">
                                        {{$user->name}}
                                    </td>
                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">
                                        {{$user->email}}
                                    </td>
                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">
                                        @if($user->deleted_at == null)
                                            <flux:badge color="green" icon="check" size="sm">Activo</flux:badge>
                                        @else
                                            <flux:badge color="red" icon="x-mark" size="sm">Inactivo</flux:badge>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-sm whitespace-nowrap text-gray-500">
                                        @if($user->hasRole('Admin'))
                                            <flux:badge color="green" size="sm" icon="user">Admin</flux:badge>
                                        @else
                                            <flux:badge color="red" icon="users" size="sm">User</flux:badge>
                                        @endif
                                    </td>
                                    <td class="flex align-middle justify-center relative py-2 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-6">
                                        <flux:button.group>
                                            <flux:button class="hover:text-yellow-600" tooltip="Editar" wire:click="edit({{ $user->id }})"><i class="fa-thin fa-edit"></i></flux:button>
                                            @if($user->deleted_at == null)
                                                <flux:button class="hover:text-red-600" tooltip="Desactivar" wire:click.prevent="deleteConfimation({{ $user->id }})"><i class="fa-thin fa-times"></i></flux:button>
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

    @include('livewire.modals.form-user')
    <livewire:modals.confirm-modal />

    <div class="pt-4 flex justify-between items-center">
        <div class="text-gray-700 text-sm">
            Resultado: {{ \Illuminate\Support\Number::format($users->total()) }}
        </div>
        {{$users->links('livewire.pagination.pagination')}}
    </div>
</div>
