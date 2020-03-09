<?php

namespace Modules\Payments\Listeners;

use Modules\Payments\Entities\Account;
use Modules\Payments\Entities\PaymentHistory;
use Modules\Payments\Events\PaymentCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddPaymentHistory
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
     * @param PaymentCompleted $event
     * @return void
     */
    public function handle(PaymentCompleted $event)
    {
        $payment_history_item = new PaymentHistory;
        $payment_history_item->from()->associate($event->from);
        $payment_history_item->to()->associate($event->to);
        $payment_history_item->for()->associate($event->for);
        $payment_history_item->amount = $event->amount;
        $payment_history_item->save();
    }
}
