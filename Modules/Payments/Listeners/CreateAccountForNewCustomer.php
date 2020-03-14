<?php

namespace Modules\Payments\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Customers\Entities\Customer;
use Modules\Customers\Events\CustomerRegistered;
use Modules\Payments\Entities\Account;
use Modules\Payments\Support\Account\AccountNumberGenerator;

class CreateAccountForNewCustomer
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
     * @param CustomerRegistered $event
     * @return void
     */
    public function handle(CustomerRegistered $event)
    {
        $account = new Account;
        $account->number = $this->numberGenerator->generate();

        /** @var Customer $customer */
        $user = $event->customer;
        $user->account()->save($account);
    }
}
