<?php

namespace Modules\RestaurantManagers\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\RestaurantManagers\Policies\RestaurantManagerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        RestaurantManager::class => RestaurantManagerPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!config()->has('restaurantmanagers')) {
            Log::alert('Configuration file of RestaurantManagers module is not published. Need to publish config so that restaurant managers can auth');
        } else {
            $app_providers = config('auth.providers');
            $app_guards = config('auth.guards');

            $module_providers = config('restaurantmanagers.providers');
            $module_guards = config('restaurantmanagers.guards');

            config()->set('auth.providers', array_merge($app_providers, $module_providers));
            config()->set('auth.guards', array_merge($app_guards, $module_guards));
        }
    }
}
