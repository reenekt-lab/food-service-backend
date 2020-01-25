<?php

return [
    'name' => 'RestaurantManagers',

    /*
     * Add Restaurant Manager Provider for Authentication
     * For details see <project folder>/config/auth.php -> "providers" key
     */
    'providers' => [
        'restaurant_managers' => [
            'driver' => 'eloquent',
            'model' => \Modules\RestaurantManagers\Entities\RestaurantManager::class,
        ],
    ],

    /*
     * Add Restaurant Manager Guard for Authentication
     * For details see <project folder>/config/auth.php -> "guards" key
     */
    'guards' => [
        'restaurant_manager' => [
            'driver' => 'jwt',
            'provider' => 'restaurant_managers',
        ],
    ],
];
