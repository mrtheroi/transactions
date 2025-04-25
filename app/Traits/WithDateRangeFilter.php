<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WithDateRangeFilter
{
    /**
     * Aplicar filtro de rango de fechas
     */
    protected function applyDateRange(Builder $query, string $dateColumn): Builder
    {
        if (!empty($this->start_date)) {
            $query->whereDate($dateColumn, '>=', $this->start_date);
        }

        if (!empty($this->end_date)) {
            $query->whereDate($dateColumn, '<=', $this->end_date);
        }

        return $query;
    }
}
