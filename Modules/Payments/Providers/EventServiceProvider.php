<?php

namespace Modules\Payments\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Customers\Events\CustomerRegistered;
use Modules\Payments\Events\BillPaid;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Payments\Listeners\AddPaymentHistory;
use Modules\Payments\Listeners\CreateAccountForNewCustomer;
use Modules\Payments\Listeners\CreateAccountForNewRestaurant;
use Modules\Payments\Listeners\SetBillStatusPaid;
use Modules\Restaurants\Events\RestaurantCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CustomerRegistered::class => [
            CreateAccountForNewCustomer::class,
        ],
        RestaurantCreated::class => [
            CreateAccountForNewRestaurant::class,
        ],

        PaymentCompleted::class => [
            AddPaymentHistory::class,
        ],

        BillPaid::class => [
            SetBillStatusPaid::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
