<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Services\TransactionService;
use App\Traits\WithSorting;
use App\Traits\WithDateRangeFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination, WithoutUrlPagination, WithSorting, WithDateRangeFilter;

    /**
     * Número de elementos por página
     */
    protected const PAGINATION_COUNT = 10;

    /**
     * Columnas permitidas para ordenar
     */
    protected $sortableColumns = [
        'name' => 'users.name',
        'amount' => 'transactions.amount',
        'type' => 'transactions.type',
        'transaction_date' => 'transactions.transaction_date',
    ];

    /**
     * Propiedades de validación
     */
    #[Validate('required|integer|exists:users,id')]
    public $user_id;

    #[Validate('required|in:credit,debit')]
    public $type;

    #[Validate('required|numeric|regex:/^\d{1,10}(\.\d{1,2})?$/')]
    public $amount;

    #[Validate('nullable|string|max:255')]
    public $description = '';

    #[Validate('required|date')]
    public $transaction_date;

    /**
     * Propiedades para el filtrado
     */
    public $search = '';
    public $start_date = '';
    public $end_date = '';

    /**
     * Estado del componente
     */
    public $open = false;
    public $selected_id = 0;

    /**
     * Servicio de transacciones
     */
    protected TransactionService $transactionService;

    /**
     * Constructor
     */
    public function boot(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Inicializar el componente
     */
    public function mount(): void
    {
        $this->initSorting('transaction_date', false);
        $this->user_id = Auth::id();
        $this->transaction_date = now()->format('Y-m-d');
    }

    /**
     * Resetea los campos del formulario
     */
    private function resetFormFields(): void
    {
        $this->reset(['user_id', 'type', 'amount', 'description', 'transaction_date', 'selected_id', 'open']);
        $this->user_id = Auth::id();
        $this->transaction_date = now()->format('Y-m-d');
    }

    /**
     * Prepara el modal para una nueva transacción
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
        if (!$this->transactionService->canManage($id)) {
            $this->dispatch('notify', message: 'You do not have permission to edit this transaction', type: 'error');
            return;
        }

        $transaction = $this->transactionService->findById($id);

        if (!$transaction) {
            $this->dispatch('notify', message: 'Transaction not found', type: 'error');
            return;
        }

        $this->user_id = $transaction->user_id;
        $this->type = $transaction->type;
        $this->amount = $transaction->amount;
        $this->description = $transaction->description;
        $this->transaction_date = Carbon::parse($transaction->transaction_date)->format('Y-m-d');
        $this->selected_id = $transaction->id;

        $this->open = true;
    }

    /**
     * Guarda o actualiza una transacción
     */
    public function save(): void
    {
        $this->validate();

        try {
            $isUpdate = $this->selected_id > 0;

            // Verificar permisos si es actualización
            if ($isUpdate && !$this->transactionService->canManage($this->selected_id)) {
                $this->dispatch('notify', message: 'You do not have permission to update this transaction', type: 'error');
                return;
            }

            $data = [
                'user_id' => $this->user_id,
                'type' => $this->type,
                'amount' => $this->amount,
                'description' => $this->description,
                'transaction_date' => $this->transaction_date,
            ];

            $transaction = $this->transactionService->save($data, $this->selected_id);

            if (!$transaction) {
                $this->dispatch('notify', message: 'Error processing transaction', type: 'error');
                return;
            }

            $this->open = false;

            $this->resetFormFields();

            $this->dispatch('$refresh');

            $message = $isUpdate ? 'Transaction updated' : 'Transaction created';
            $this->dispatch('notify', message: $message, type: 'success');

        } catch (\Exception $e) {
            logger()->error('Error saving transaction: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error saving transaction', type: 'error');
        }
    }

    /**
     * Envía confirmación para eliminar
     */
    public function deleteConfirmation(int $id): void
    {
        $this->dispatch('showConfirmationModal', transactionId: $id)->to('livewire.modals.confirm-modal');
    }

    /**
     * Elimina una transacción
     */
    #[On('deleteConfirmed')]
    public function destroy(int $id): void
    {
        try {
            if (!$this->transactionService->canManage($id)) {
                $this->dispatch('notify', message: 'You do not have permission to delete this transaction', type: 'error');
                return;
            }

            $success = $this->transactionService->destroy($id);

            if (!$success) {
                $this->dispatch('notify', message: 'Transaction not found', type: 'error');
                return;
            }

            $this->dispatch('notify', message: 'Transaction deleted', type: 'success');
        } catch (\Exception $e) {
            logger()->error('Error deleting transaction: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error deleting transaction', type: 'error');
        }
    }

    /**
     * Limpiar todos los filtros
     */
    public function clearFilters(): void
    {
        $this->search = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->resetPage();
    }

    /**
     * Renderizar la vista
     */
    public function render()
    {
        $transactions = $this->transactionService->getFilteredTransactions(
            $this->search,
            $this->start_date,
            $this->end_date,
            $this->sortColumn,
            $this->sortDirection,
            $this->sortableColumns,
            self::PAGINATION_COUNT
        );

        return view('livewire.transactions.transaction', compact('transactions'));
    }
}
