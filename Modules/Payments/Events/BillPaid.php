<?php

namespace Modules\Payments\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Payments\Entities\Bill;

class BillPaid
{
    use SerializesModels;

    public $bill;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }
}
