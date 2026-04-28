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
use Module\Client\Infrastructure\Gateways\ClientModuleGateway;
use Module\Client\Infrastructure\Persistence\Eloquent\Repositories\EloquentClientRepository;
use Module\Product\Core\Contracts\ProductRepositoryContract;
use Module\Product\Core\Contracts\ProductStockLedgerRepositoryContract;
use Module\Product\Infrastructure\Gateways\ProductModuleGateway;
use Module\Product\Infrastructure\Gateways\ProductQueryGateway;
use Module\Product\Infrastructure\Persistence\Eloquent\Repositories\EloquentProductRepository;
use Module\Product\Infrastructure\Persistence\Eloquent\Repositories\EloquentProductStockLedgerRepository;
use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Sale\Infrastructure\Gateways\SaleModuleGateway;
use Module\Sale\Infrastructure\Persistence\Eloquent\Repositories\EloquentSaleRepository;
use Module\Service\Core\Contracts\ServiceRepositoryContract;
use Module\Service\Infrastructure\Gateways\ServiceModuleGateway;
use Module\Service\Infrastructure\Gateways\ServiceQueryGateway;
use Module\Service\Infrastructure\Persistence\Eloquent\Repositories\EloquentServiceRepository;
use Module\Shared\Core\Contracts\ClientModuleGatewayContract;
use Module\Shared\Core\Contracts\ProductModuleGatewayContract;
use Module\Shared\Core\Contracts\ProductQueryServiceContract;
use Module\Shared\Core\Contracts\SaleModuleGatewayContract;
use Module\Shared\Core\Contracts\ServiceModuleGatewayContract;
use Module\Shared\Core\Contracts\ServiceQueryServiceContract;

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

        $this->app->bind(
            ProductQueryServiceContract::class,
            ProductQueryGateway::class,
        );

        $this->app->bind(
            ServiceQueryServiceContract::class,
            ServiceQueryGateway::class,
        );

        $this->app->bind(
            SaleModuleGatewayContract::class,
            SaleModuleGateway::class,
        );

        $this->app->bind(
            ClientModuleGatewayContract::class,
            ClientModuleGateway::class,
        );

        $this->app->bind(
            ProductModuleGatewayContract::class,
            ProductModuleGateway::class,
        );

        $this->app->bind(
            ServiceModuleGatewayContract::class,
            ServiceModuleGateway::class,
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
