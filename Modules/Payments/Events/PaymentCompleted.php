<?php

namespace Modules\Payments\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Payments\Entities\Account;
use Modules\Payments\Entities\Bill;

class PaymentCompleted
{
    public $from;
    public $to;
    public $for;
    public $amount;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Account|mixed $from
     * @param Account|mixed $to
     * @param Bill|mixed $for
     * @param float $amount
     */
    public function __construct($from, $to, $for, $amount)
    {
        $this->from = $from;
        $this->to = $to;
        $this->for = $for;
        $this->amount = $amount;
    }
}
