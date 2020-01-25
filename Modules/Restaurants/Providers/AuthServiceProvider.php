<?php

namespace Modules\Restaurants\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Restaurants\Entities\Food;
use Modules\Restaurants\Entities\Restaurant;
use Modules\Restaurants\Policies\FoodPolicy;
use Modules\Restaurants\Policies\RestaurantPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Restaurant::class => RestaurantPolicy::class,
        Food::class => FoodPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
