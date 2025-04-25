<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Services\UserService;
use App\Traits\WithSorting; // Asegúrate de tener este trait
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination, WithoutUrlPagination, WithSorting;

    /**
     * Número de elementos por página
     */
    protected const PAGINATION_COUNT = 10;

    /**
     * Columnas permitidas para ordenar
     */
    protected $sortableColumns = [
        'name' => 'name',
        'email' => 'email',
        'role' => 'roles.name',
        'status' => 'deleted_at',
    ];

    /**
     * Propiedades de validación
     */
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|unique:users,email')]
    public $email = '';

    #[Validate('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    #[Validate('nullable|exists:roles,id')]
    public $role_id = null;

    /**
     * Propiedades para el filtrado
     */
    public $search = '';

    /**
     * Estado del componente
     */
    public $open = false;
    public $selected_id = 0;

    /**
     * Servicio de usuarios
     */
    protected UserService $userService;

    /**
     * Constructor
     */
    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Inicializar el componente
     */
    public function mount(): void
    {
        $this->initSorting('name', true); // Ordenar por nombre ascendente por defecto
    }

    /**
     * Resetea los campos del formulario
     */
    private function resetFormFields(): void
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role_id', 'selected_id', 'open']);
    }

    /**
     * Eventos de actualización
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Prepara el modal para un nuevo usuario
     */
    public function create(): void
    {
        $this->resetFormFields();
        $this->resetValidation();
        $this->open = true;
    }

    /**
     * Prepara los campos para editar
     */
    public function edit(int $id): void
    {
        $user = $this->userService->findById($id);

        if (!$user) {
            $this->dispatch('notify', message: 'User not found', type: 'error');
            return;
        }

        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_id = $user->roles->first()->id ?? null;

        $this->resetValidation();
        $this->open = true;
    }

    /**
     * Guarda o actualiza un usuario
     */
    public function save(): void
    {
        // Modificar las reglas de validación para el email en caso de actualización
        if ($this->selected_id) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$this->selected_id,
                'password' => $this->password ? 'min:8|confirmed' : '',
                'role_id' => 'nullable|exists:roles,id',
            ]);
        } else {
            $this->validate();
        }

        try {
            $isUpdate = $this->selected_id > 0;

            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            // Solo incluir password si se proporcionó uno nuevo
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $user = $this->userService->save($data, $this->selected_id);

            if (!$user) {
                $this->dispatch('notify', message: 'Error processing user', type: 'error');
                return;
            }

            // Asignar rol si se seleccionó uno
            if ($this->role_id) {
                $this->userService->assignRole($user, $this->role_id);
            }

            // Cerrar el modal primero
            $this->open = false;

            // Resetear los campos del formulario
            $this->resetFormFields();

            // Forzar una actualización de la tabla
            $this->dispatch('$refresh');

            // Mensaje de éxito después del refresh
            $message = $isUpdate ? 'User updated successfully' : 'User created successfully';
            $this->dispatch('notify', message: $message, type: 'success');

        } catch (\Exception $e) {
            logger()->error('Error saving user: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error saving user', type: 'error');
        }
    }

    /**
     * Envía confirmación para eliminar
     */
    public function deleteConfirmation(int $id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to('livewire.modals.confirm-modal');
    }

    /**
     * Elimina un usuario
     */
    #[On('deleteConfirmed')]
    public function destroy(int $id): void
    {
        try {
            $success = $this->userService->destroy($id);

            if (!$success) {
                $this->dispatch('notify', message: 'User not found', type: 'error');
                return;
            }

            // Forzar actualización de la tabla
            $this->dispatch('$refresh');

            // Notificación de éxito
            $this->dispatch('notify', message: 'User deleted successfully', type: 'success');
        } catch (\Exception $e) {
            logger()->error('Error deleting user: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error deleting user', type: 'error');
        }
    }

    /**
     * Renderizar la vista
     */
    public function render()
    {
        $users = $this->userService->getFilteredUsers(
            $this->search,
            $this->sortColumn,
            $this->sortDirection,
            true, // incluir usuarios eliminados
            self::PAGINATION_COUNT,
            $this->sortableColumns
        );

        $roles = Role::all();

        return view('livewire.users.users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
