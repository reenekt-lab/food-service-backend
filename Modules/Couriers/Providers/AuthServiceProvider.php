<?php

namespace Modules\Couriers\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Couriers\Entities\Courier;
use Modules\Couriers\Policies\CourierPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Courier::class => CourierPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!config()->has('couriers')) {
            Log::alert('Configuration file of Couriers module is not published. Need to publish config so that couriers can auth');
        } else {
            $app_providers = config('auth.providers');
            $app_guards = config('auth.guards');

            $module_providers = config('couriers.providers');
            $module_guards = config('couriers.guards');

            config()->set('auth.providers', array_merge($app_providers, $module_providers));
            config()->set('auth.guards', array_merge($app_guards, $module_guards));
        }
    }
}
