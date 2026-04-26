<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Module\Auth\Core\Contracts\UserAuthenticationServiceContract;
use Module\Auth\Infrastructure\Security\LaravelUserAuthenticationService;
use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Infrastructure\Persistence\Eloquent\Repositories\EloquentClientRepository;
use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Infrastructure\Persistence\Eloquent\Repositories\EloquentProductRepository;
use Module\Product\Infrastructure\Persistence\Eloquent\Repositories\EloquentProductStockLedgerRepository;
use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Sale\Infrastructure\Persistence\Eloquent\Repositories\EloquentSaleRepository;
use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Infrastructure\Persistence\Eloquent\Repositories\EloquentServiceRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserAuthenticationServiceContract::class,
            LaravelUserAuthenticationService::class,
        );

        $this->app->bind(
            ClientRepositoryContract::class,
            EloquentClientRepository::class,
        );

        $this->app->bind(
            ProductRepositoryContract::class,
            EloquentProductRepository::class,
        );

        $this->app->bind(
            ProductStockLedgerRepositoryContract::class,
            EloquentProductStockLedgerRepository::class,
        );

        $this->app->bind(
            ServiceRepositoryContract::class,
            EloquentServiceRepository::class,
        );

        $this->app->bind(
            SaleRepositoryContract::class,
            EloquentSaleRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
