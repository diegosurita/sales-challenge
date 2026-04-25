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
