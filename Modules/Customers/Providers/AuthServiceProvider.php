<?php

namespace Modules\Customers\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Customers\Entities\Customer;
use Modules\Customers\Policies\CustomerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Customer::class => CustomerPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!config()->has('customers')) {
            Log::alert('Configuration file of Customers module is not published. Need to publish config so that customers can auth');
        } else {
            $app_providers = config('auth.providers');
            $app_guards = config('auth.guards');

            $module_providers = config('customers.providers');
            $module_guards = config('customers.guards');

            config()->set('auth.providers', array_merge($app_providers, $module_providers));
            config()->set('auth.guards', array_merge($app_guards, $module_guards));
        }
    }
}
