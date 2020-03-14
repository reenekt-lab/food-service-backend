<?php

namespace Modules\Payments\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Customers\Entities\Customer;
use Modules\Payments\Entities\Account;
use Modules\Payments\Entities\Bill;
use Modules\Payments\Support\Account\AccountNumberGenerator;
use Modules\Payments\Support\Account\UUIDAccountNumberGenerator;
use Modules\Restaurants\Entities\Restaurant;

class PaymentsServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Payments', 'Database/Migrations'));

        Relation::morphMap([
            'customer' => Customer::class,
            'restaurant' => Restaurant::class,
            'account' => Account::class,
            'bill' => Bill::class,
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->app->bind(AccountNumberGenerator::class, config('payments.accounts.number_generator'));
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Payments', 'Config/config.php') => config_path('payments.php'),
        ], ['config', 'food-service', 'food-service-config', 'food-service-init']);
        $this->mergeConfigFrom(
            module_path('Payments', 'Config/config.php'), 'payments'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/payments');

        $sourcePath = module_path('Payments', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/payments';
        }, \Config::get('view.paths')), [$sourcePath]), 'payments');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/payments');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'payments');
        } else {
            $this->loadTranslationsFrom(module_path('Payments', 'Resources/lang'), 'payments');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('Payments', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
