<?php

namespace Modules\RestaurantManagers\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class RestaurantManagersServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('RestaurantManagers', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('RestaurantManagers', 'Config/config.php') => config_path('restaurantmanagers.php'),
        ], ['config', 'food-service', 'food-service-config', 'food-service-init']);
        $this->mergeConfigFrom(
            module_path('RestaurantManagers', 'Config/config.php'), 'restaurantmanagers'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/restaurantmanagers');

        $sourcePath = module_path('RestaurantManagers', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/restaurantmanagers';
        }, \Config::get('view.paths')), [$sourcePath]), 'restaurantmanagers');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/restaurantmanagers');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'restaurantmanagers');
        } else {
            $this->loadTranslationsFrom(module_path('RestaurantManagers', 'Resources/lang'), 'restaurantmanagers');
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
            app(Factory::class)->load(module_path('RestaurantManagers', 'Database/factories'));
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
