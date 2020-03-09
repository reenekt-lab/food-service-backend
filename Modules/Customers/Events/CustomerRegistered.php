<?php

namespace Modules\Customers\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Customers\Entities\Customer;

class CustomerRegistered
{
    use SerializesModels;

    /**
     * @var Customer Registered customer
     */
    public $customer;

    /**
     * Create a new event instance.
     *
     * @param Customer $customer Registered customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}
