<?php

namespace Modules\Restaurants\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Restaurants\Entities\Restaurant;

class RestaurantCreated
{
    use SerializesModels;

    /**
     * Created restaurant
     *
     * @var Restaurant
     */
    public $restaurant;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
