<?php

namespace Modules\Payments\Listeners;

use Modules\Payments\Entities\Bill;
use Modules\Payments\Events\BillPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetBillStatusPaid
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BillPaid $event
     * @return void
     */
    public function handle(BillPaid $event)
    {
        $event->bill->update([
            'status' => Bill::STATUS_PAID,
        ]);
    }
}
