<?php

return [
    'name' => 'Payments',

    'accounts' => [

        /*
         * Генератор номера счета
         */
        'number_generator' => \Modules\Payments\Support\Account\UUIDAccountNumberGenerator::class
    ],
];
