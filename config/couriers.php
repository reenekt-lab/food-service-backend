<?php

return [
    'name' => 'Couriers',

    /*
     * Add Courier Provider for Authentication
     * For details see <project folder>/config/auth.php -> "providers" key
     */
    'providers' => [
        'couriers' => [
            'driver' => 'eloquent',
            'model' => \Modules\Couriers\Entities\Courier::class,
        ],
    ],

    /*
     * Add Courier Guard for Authentication
     * For details see <project folder>/config/auth.php -> "guards" key
     */
    'guards' => [
        'courier' => [
            'driver' => 'passport',
            'provider' => 'couriers',
        ],
    ],
];
