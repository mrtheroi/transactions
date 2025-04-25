<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class TransactionService
{
    /**
     * Guardar una transacción (crear nueva o actualizar existente)
     */
    public function save(array $data, ?int $id = null): ?Transaction
    {
        return DB::transaction(function () use ($data, $id) {
            if ($id) {
                // Actualizar existente
                $transaction = $this->findById($id);

                if (!$transaction) {
                    return null;
                }

                $transaction->update($data);
                return $transaction;
            } else {
                // Crear nuevo
                return Transaction::create($data);
            }
        });
    }

    /**
     * Eliminar una transacción
     */
    public function destroy(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = $this->findById($id);

            if (!$transaction) {
                return false;
            }

            return $transaction->delete();
        });
    }

    /**
     * Encontrar una transacción por su ID
     */
    public function findById(int $id): ?Transaction
    {
        return Transaction::find($id);
    }

    /**
     * Verificar si un usuario puede gestionar una transacción
     */
    public function canManage(int $transactionId): bool
    {
        $transaction = $this->findById($transactionId);

        if (!$transaction) {
            return false;
        }

        // El administrador puede gestionar cualquier transacción
        if (Auth::user()->hasRole('Admin')) {
            return true;
        }

        // El usuario solo puede gestionar sus propias transacciones
        return $transaction->user_id === Auth::id();
    }

    /**
     * Obtener transacciones filtradas y paginadas
     */
    public function getFilteredTransactions(
        ?string $search = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $sortColumn = null,
        ?string $sortDirection = 'asc',
        array $sortableColumns = [],
        int $perPage = 10
    ) {
        return Transaction::query()
            ->select('transactions.*')
            ->with('user')
            ->when($search, function (Builder $query) use ($search) {
                $query->join('users', 'users.id', '=', 'transactions.user_id')
                    ->where(function (Builder $q) use ($search) {
                        $q->where('users.name', 'like', "%{$search}%")
                            ->orWhere('transactions.amount', 'like', "%{$search}%")
                            ->orWhere('transactions.description', 'like', "%{$search}%");
                    });
            })
            ->when($startDate, function (Builder $query) use ($startDate) {
                $query->whereDate('transactions.transaction_date', '>=', $startDate);
            })
            ->when($endDate, function (Builder $query) use ($endDate) {
                $query->whereDate('transactions.transaction_date', '<=', $endDate);
            })
            ->when($sortColumn && isset($sortableColumns[$sortColumn]), function (Builder $query) use ($sortColumn, $sortDirection, $sortableColumns) {
                $column = $sortableColumns[$sortColumn];

                if (str_contains($column, 'users.')) {
                    $query->leftJoinIf(
                        !$query->getQuery()->joins || !collect($query->getQuery()->joins)->pluck('table')->contains('users'),
                        'users',
                        'users.id',
                        '=',
                        'transactions.user_id'
                    );
                }

                $query->orderBy($column, $sortDirection);
            })
            ->when(!Auth::user()->hasRole('Admin'), function (Builder $query) {
                $query->where('user_id', Auth::id());
            })
            ->paginate($perPage);
    }
}
