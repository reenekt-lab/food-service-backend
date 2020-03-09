<?php

namespace Modules\Payments\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Payments\Entities\Account;
use Modules\Payments\Support\Account\AccountNumberGenerator;
use Modules\Restaurants\Entities\Restaurant;
use Modules\Restaurants\Events\RestaurantCreated;

class CreateAccountForNewRestaurant
{
    /** @var AccountNumberGenerator Генератор номера счета */
    public $numberGenerator;

    /**
     * Create the event listener.
     *
     * @param AccountNumberGenerator $numberGenerator
     */
    public function __construct(AccountNumberGenerator $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * Handle the event.
     *
     * @param RestaurantCreated $event
     * @return void
     */
    public function handle(RestaurantCreated $event)
    {
        $account = new Account;
        $account->number = $this->numberGenerator->generate();

        /** @var Restaurant $user */
        $restaurant = $event->restaurant;
        $restaurant->account()->save($account);
    }
}
