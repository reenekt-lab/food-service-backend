<?php

namespace Modules\RestaurantManagers\Events;

use Illuminate\Queue\SerializesModels;
use Modules\RestaurantManagers\Entities\RestaurantManager;

class RestaurantManagerRegistered
{
    use SerializesModels;

    /**
     * @var RestaurantManager Registered Restaurant Manager
     */
    public $restaurantManager;

    /**
     * Create a new event instance.
     *
     * @param RestaurantManager $restaurantManager Registered Restaurant Manager
     */
    public function __construct(RestaurantManager $restaurantManager)
    {
        $this->restaurantManager = $restaurantManager;
    }
}
