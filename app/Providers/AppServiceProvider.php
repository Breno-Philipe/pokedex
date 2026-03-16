<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider responsible for registering
 * authorization services within the application.
 *
 * This provider maps application models to their
 * corresponding policies, enabling Laravel's
 * authorization system (Gates and Policies).
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * Associates models with their authorization policies.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Pokemon::class => PokemonPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

}
