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
    protected function applySorting(Builder $query): Builder
    {
        if (empty($this->sortColumn) || !isset($this->sortableColumns[$this->sortColumn])) {
            return $query;
        }

        $column = $this->sortableColumns[$this->sortColumn];

        // Si estamos ordenando por una columna que requiere un join
        if (str_contains($column, 'users.')) {
            $query->leftJoinIf(
                !$query->getQuery()->joins || !collect($query->getQuery()->joins)->pluck('table')->contains('users'),
                'users',
                'users.id',
                '=',
                'transactions.user_id'
            );
        }

        return $query->orderBy($column, $this->sortDirection);
    }
}
