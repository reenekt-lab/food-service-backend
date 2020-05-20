<?php

return [
    'name' => 'Customers',

    /*
     * Add Customer Provider for Authentication
     * For details see <project folder>/config/auth.php -> "providers" key
     */
    'providers' => [
        'customers' => [
            'driver' => 'eloquent',
            'model' => \Modules\Customers\Entities\Customer::class,
        ],
    ],

    /*
     * Add Customer Guard for Authentication
     * For details see <project folder>/config/auth.php -> "guards" key
     */
    'guards' => [
        'customer' => [
            'driver' => 'passport',
            'provider' => 'customers',
        ],
    ],
];
