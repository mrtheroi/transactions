<div>
    <flux:modal wire:model="open" name="store-user" class="md:w-200">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ $selected_id > 0 ? 'Editar registro' : 'Crear un nuevo usuario'}}
                </flux:heading>
            </div>

            <flux:input label="Name" wire:model="name" placeholder="Your name" />
            <flux:error name="name" />

            <flux:input label="Password" type="password" wire:model="password" placeholder="Your password" />
            <flux:error name="password" />

            <flux:input label="Email" wire:model="email" placeholder="Your email" />
            <flux:error name="email" />

            <flux:select label="Roles" wire:model="rol_id">
                <flux:select.option>Seleciona un Rol</flux:select.option>
                @foreach($roles as $rol)
                    <flux:select.option value="{{$rol->id}}">{{$rol->name}}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit"  wire:click="$set('open', false)">Cancel</flux:button>
                @if($selected_id < 1)
                    <flux:button type="submit" variant="primary" class="ml-2" wire:click.prevent="save">Guardar</flux:button>
                @else
                    <flux:button type="submit" variant="primary" wire:click.prevent="update" class="ml-2">Guardar Cambios</flux:button>
                @endif
            </div>
        </div>
    </flux:modal>
</div>
