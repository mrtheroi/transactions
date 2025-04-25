<?php

namespace App\Livewire\Users;

use App\Livewire\Modals\ConfirmModal;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Validate('required|min:6')]
    public $name;
    #[Validate('required|email')]
    public $email;
    #[Validate('required|min:8')]
    public $password;

    ## no validate vars ##
    public User $user;
    public $search = '';
    public $open = false;

    public $sortCol;
    public $sortAsc = false;

    public $user_id;
    public $selected_id;

    public function mount(): void
    {
        $this->user = new User();
        $this->selected_id = 0;
        $this->show = false;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function applySorting($query)
    {
        if ($this->sortCol) {
            $colum = match ($this->sortCol)
            {
                'name' => 'name',
                'status' => 'deleted_at',
            };
            $query->orderBy($colum, $this->sortAsc ? 'asc' : 'desc');
        }
        return $query;
    }

    public function sortBy($colum)
    {
        if ($this->sortCol === $colum) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortCol = $colum;
            $this->sortAsc = false;
        }

    }

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere(function ($query) {
                    if (stripos('Activo', $this->search) !== false) {
                        $query->whereNull('deleted_at');
                    } elseif (stripos('Inactivo', $this->search) !== false) {
                        $query->whereNotNull('deleted_at');
                    }
                });
    }


    public function render()
    {
        $query = $this->user->withTrashed();
        $query = $this->applySearch($query);
        $query = $this->applySorting($query);
        $users = $query->paginate(10);
        $roles = Role::all();
        return view('livewire.users.users', compact('users', 'roles'));
    }

    public function save()
    {
        $this->validate();
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->dispatch('notify', message: 'Usuario guardado con éxito', type: 'success');
        $this->reset();
    }

    public function edit($id)
    {
        $user = User::find($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = $user->password;
        $this->selected_id = $user->id;
        $this->open = true;
    }

    public function update()
    {
        $this->validate();

        User::find($this->selected_id)->update([
            'name' => $this->name,
            'password' => $this->password ? Hash::make($this->password) : null,
        ]);

        $this->reset();
        $this->dispatch('notify', message: 'Usuario modificado con éxito', type: 'success');
    }
    public function deleteConfimation($id)
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);
    }

    #[On('deleteConfirmed')]
    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        $user->delete();
    }
}
