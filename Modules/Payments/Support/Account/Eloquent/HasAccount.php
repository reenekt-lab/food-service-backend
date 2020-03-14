<?php


namespace Modules\Payments\Support\Account\Eloquent;


use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;
use Modules\Payments\Entities\Account;
use Modules\Payments\Entities\Bill;
use Modules\Payments\Events\BillPaid;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Payments\Support\Account\Exceptions\PaymentException;

/**
 * Trait HasAccount
 * @package Modules\Payments\Support\Eloquent
 *
 * @property-read Account $account
 */
trait HasAccount
{
    /**
     * Счет, который принадлежит к данной модели
     *
     * @return MorphOne
     */
    public function account()
    {
        return $this->morphOne(Account::class, 'owner');
    }

    /**
     * Оплата заказа по выставленному счету
     *
     * @param Bill $bill
     * @throws PaymentException
     */
    public function billPay(Bill $bill)
    {
        $current_account = $this->account;
        $target_account = $bill->restaurant->account;
        $amount = $bill->amount;

        if ($current_account->balance <= 0) {
            throw new PaymentException('No money on balance');
        }
        if ($amount > $current_account->balance) {
            throw new PaymentException('Not enough money on balance');
        }

        /*
         * не самый безопасный способ перечисления средств, но в рамках учебного проекта лучшего решения нет
         * (вообще оплата должна происходить вне сервиса, а на стороне платежных систем/банков, а в
         * сервисе должна быть только обработка результата, приходящего с платежной )
         */
        $current_account->balance -= $amount;
        $target_account->balance += $amount;

        try {
            DB::transaction(function () use ($current_account, $target_account) {
                $current_account->save();
                $target_account->save();
            });
        } catch (\Throwable $e) {
            throw new PaymentException('Error processing database transaction');
        }

        /**
         * Событие перечисления денежных средств на счет. Добавляет запись в историю платежей.
         * @see \Modules\Payments\Listeners\AddPaymentHistory
         */
        event(new PaymentCompleted($current_account, $target_account, $bill, $amount));

        /**
         * Событие полной оплаты по выставленному счету. Изменяет статус выставленного счета на "Оплачен".
         * @see \Modules\Payments\Listeners\SetBillStatusPaid
         */
        event(new BillPaid($bill));
    }
}
