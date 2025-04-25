<?php

namespace App\Livewire\Dashboard;

use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Calcular número de transacciones de crédito
        $creditTransactions = Transaction::where('type', 'credit')->count();

        // Calcular número de transacciones de débito
        $debitTransactions = Transaction::where('type', 'debit')->count();

        $activeUsers = User::withoutTrashed()->count();

        // Calcular usuarios inactivos (eliminados mediante soft delete)
        $inactiveUsers = User::onlyTrashed()->count();

        // Transacciones de este mes
        $transactionsThisMonth = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->count();

        // Total de transacciones
        $totalTransactions = Transaction::count();

        // Calcular porcentajes (evitando división por cero)
        $totalUsers = $activeUsers + $inactiveUsers;
        $activeUsersPercent = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0;
        $inactiveUsersPercent = $totalUsers > 0 ? round(($inactiveUsers / $totalUsers) * 100) : 0;

        $totalTypeTransactions = $creditTransactions + $debitTransactions;
        $creditPercent = $totalTypeTransactions > 0 ? round(($creditTransactions / $totalTypeTransactions) * 100) : 0;
        $debitPercent = $totalTypeTransactions > 0 ? round(($debitTransactions / $totalTypeTransactions) * 100) : 0;

        $stats = [
            [
                'title' => 'Usuarios activos',
                'value' => $activeUsers,
                'icon' => 'fa-light fa-user-check fa-xl',
                'color' => 'text-green-400',
                'border_color' => 'border-green-200',
                'info' => "{$activeUsersPercent}% del total de usuarios",
            ],
            [
                'title' => 'Usuarios inactivos',
                'value' => $inactiveUsers,
                'icon' => 'fa-light fa-user-slash fa-xl',
                'color' => 'text-rose-400',
                'border_color' => 'border-rose-200',
                'info' => "{$inactiveUsersPercent}% del total de usuarios",
            ],
            [
                'title' => 'Transacciones de crédito',
                'value' => $creditTransactions,
                'icon' => 'fa-light fa-arrow-up-right-dots fa-xl',
                'color' => 'text-blue-400',
                'border_color' => 'border-blue-200',
                'info' => "{$creditPercent}% del total de transacciones",
            ],
            [
                'title' => 'Transacciones de débito',
                'value' => $debitTransactions,
                'icon' => 'fa-light fa-arrow-down-right fa-xl',
                'color' => 'text-amber-400',
                'border_color' => 'border-amber-200',
                'info' => "{$debitPercent}% del total de transacciones",
            ],
            [
                'title' => 'Transacciones totales',
                'value' => $totalTransactions,
                'icon' => 'fa-light fa-wallet fa-xl',
                'color' => 'text-indigo-400',
                'border_color' => 'border-indigo-200',
                'info' => "Hasta la fecha",
            ],
            [
                'title' => 'Transacciones este mes',
                'value' => $transactionsThisMonth,
                'icon' => 'fa-light fa-calendar-check fa-xl',
                'color' => 'text-emerald-400',
                'border_color' => 'border-emerald-200',
                'info' => "Período: " . now()->startOfMonth()->format('d/m/Y') . " - " . now()->endOfMonth()->format('d/m/Y'),
            ],
        ];

        return view('livewire.dashboard.dashboard', compact('stats'));
    }
}
