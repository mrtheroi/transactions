<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WithSorting
{
    public $sortColumn;
    public $sortDirection = 'asc';

    /**
     * Inicializar el ordenamiento
     */
    public function initSorting(string $column = null, bool $ascending = true): void
    {
        $this->sortColumn = $column;
        $this->sortDirection = $ascending ? 'asc' : 'desc';
    }

    /**
     * Cambiar el ordenamiento
     */
    public function sortBy(string $column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Aplicar ordenamiento a la consulta
     */
    /**
     * Aplicar ordenamiento a la consulta
     */
    protected function applySorting(Builder $query): Builder
    {
        if (empty($this->sortColumn) || !isset($this->sortableColumns[$this->sortColumn])) {
            return $query;
        }

        $column = $this->sortableColumns[$this->sortColumn];

        if ($this->sortColumn === 'role') {
            $query->select('users.*')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_type', '=', User::class)
                ->orderBy('roles.name', $this->sortDirection);

            return $query;
        }

        if ($this->sortColumn === 'status') {

            $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';
            $query->orderByRaw("CASE WHEN deleted_at IS NULL THEN 0 ELSE 1 END {$direction}");

            return $query;
        }

        return $query->orderBy($column, $this->sortDirection);
    }
}
