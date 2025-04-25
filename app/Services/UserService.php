<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Guardar un usuario (crear nuevo o actualizar existente)
     */
    public function save(array $data, ?int $id = null): ?User
    {
        return DB::transaction(function () use ($data, $id) {
            if ($id) {
                // Actualizar existente
                $user = $this->findById($id);

                if (!$user) {
                    return null;
                }

                $user->update($data);
                return $user;
            } else {
                // Crear nuevo
                return User::create($data);
            }
        });
    }

    /**
     * Asignar un rol a un usuario
     */
    public function assignRole(User $user, int $roleId): void
    {
        $role = Role::find($roleId);

        if ($role) {
            DB::transaction(function () use ($user, $role) {
                // Eliminar roles existentes
                $user->syncRoles([]);
                // Asignar nuevo rol
                $user->assignRole($role);
            });
        }
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $user = $this->findById($id);

            if (!$user) {
                return false;
            }

            // Si usas SoftDeletes
            return $user->delete();
        });
    }

    /**
     * Encontrar un usuario por su ID
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Obtener usuarios filtrados y paginados
     */
    /**
     * Obtener usuarios filtrados y paginados
     */
    public function getFilteredUsers(
        ?string $search = null,
        ?string $sortColumn = 'name',
        ?string $sortDirection = 'asc',
        bool $withTrashed = false,
        int $perPage = 10,
        array $sortableColumns = []
    ) {
        $query = $withTrashed ? User::withTrashed() : User::query();

        // Aplicar búsqueda
        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere(function ($query) use ($search) {
                        if (stripos('Activo', $search) !== false) {
                            $query->whereNull('deleted_at');
                        } elseif (stripos('Inactivo', $search) !== false) {
                            $query->whereNotNull('deleted_at');
                        }
                    });
            });
        }

        // Aplicar ordenamiento especializado
        if ($sortColumn && isset($sortableColumns[$sortColumn])) {
            $column = $sortableColumns[$sortColumn];

            // Si estamos ordenando por rol, necesitamos hacer un join con la tabla roles
            if ($sortColumn === 'role') {
                $query->select('users.*')
                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_type', '=', User::class)
                    ->orderBy('roles.name', $sortDirection);
            }
            // Si estamos ordenando por estatus, ordenamos por deleted_at
            else if ($sortColumn === 'status') {
                // Si deleted_at es NULL, el usuario está activo
                $direction = $sortDirection === 'asc' ? 'asc' : 'desc';
                $query->orderByRaw("CASE WHEN deleted_at IS NULL THEN 0 ELSE 1 END {$direction}");
            }
            // Para otras columnas, ordenamiento normal
            else {
                $query->orderBy($column, $sortDirection);
            }
        }

        // Paginar resultados
        return $query->paginate($perPage);
    }
}
