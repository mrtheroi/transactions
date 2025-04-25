<?php

namespace App\Providers;

use App\Services\TransactionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TransactionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('leftJoinIf', function ($condition, $table, $first, $operator = null, $second = null) {
            if ($condition) {
                return $this->leftJoin($table, $first, $operator, $second);
            }
            return $this;
        });
    }
}
