<?php

namespace Modules\Payments\Tests\Feature;

use Modules\Customers\Entities\Customer;
use Modules\Orders\Entities\Order;
use Modules\Payments\Entities\Bill;
use Modules\Payments\Support\Account\Eloquent\HasAccount;
use Modules\Payments\Support\Account\Exceptions\PaymentException;
use Modules\Restaurants\Entities\Restaurant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillPaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тестирование оплаты по выставленному счету клиентом, совершившим заказ
     */
    public function testCustomerBillPayToRestaurant()
    {
        $customer_class_traits = class_uses_recursive(Customer::class);
        $this->assertContains(HasAccount::class, $customer_class_traits, 'Customer class should have HasAccount trait');

        $restaurant_class_traits = class_uses_recursive(Restaurant::class);
        $this->assertContains(HasAccount::class, $restaurant_class_traits, 'Restaurant class should have HasAccount trait');

        /** @var Customer $customer */
        $customer = factory(Customer::class)->create();
        $customer->account()->create([
            'number' => 'bf66b447-c939-4cc6-8222-f93403369f7e'
        ]);

        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();
        $restaurant->account()->create([
            'number' => 'c65a7b21-ec66-4849-adf8-5dc6a1388525'
        ]);

        /** @var Order $order */
        $order = factory(Order::class)->create([
            'customer_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
        ]);

        $order_amount = 500;
        $customer_balance = 5000;

        /** @var Bill $bill */
        $bill = factory(Bill::class)->create([
            'customer_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
            'order_id' => $order->id,
            'amount' => $order_amount,
        ]);

        // ручное добавление средств на счет для проведения тестирования
        $customer->account->update([
            'balance' => $customer_balance,
        ]);

        $this->assertEqualsWithDelta(0, $restaurant->account->balance, 0.01, 'Restautant account\'s balance not equals to 0');

        try {
            $customer->billPay($bill);
        } catch (PaymentException $e) {
            $this->fail('Exception catch while bill pay processing: ' . $e->getMessage());
        }

        $restaurant->refresh();
        $this->assertEqualsWithDelta($order_amount, $restaurant->account->balance, 0.01);
        $customer->refresh();
        $this->assertEqualsWithDelta($customer_balance - $order_amount, $customer->account->balance, 0.01);

        $bill->refresh();
        $this->assertEquals(Bill::STATUS_PAID, $bill->status);

        $this->assertDatabaseHas('payment_history', [
            'from_type' => 'account',
            'from_id' => $customer->account->id,
            'to_type' => 'account',
            'to_id' => $restaurant->account->id,
            'for_type' => 'bill',
            'for_id' => $bill->id,
            'amount' => $order_amount,
        ]);
    }

    /**
     * Тестирование попытки оплаты по выставленному счету клиентом, у которого недостаточно денег на счете
     */
    public function testCustomerBillPayToRestaurantNotEnoughMoney()
    {
        $customer_class_traits = class_uses_recursive(Customer::class);
        $this->assertContains(HasAccount::class, $customer_class_traits, 'Customer class should have HasAccount trait');

        $restaurant_class_traits = class_uses_recursive(Restaurant::class);
        $this->assertContains(HasAccount::class, $restaurant_class_traits, 'Restaurant class should have HasAccount trait');

        /** @var Customer $customer */
        $customer = factory(Customer::class)->create();
        $customer->account()->create([
            'number' => 'bf66b447-c939-4cc6-8222-f93403369f7f'
        ]);

        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();
        $restaurant->account()->create([
            'number' => 'c65a7b21-ec66-4849-adf8-5dc6a1388526'
        ]);

        /** @var Order $order */
        $order = factory(Order::class)->create([
            'customer_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
        ]);

        $order_amount = 500;
        $customer_balance = 300;

        /** @var Bill $bill */
        $bill = factory(Bill::class)->create([
            'customer_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
            'order_id' => $order->id,
            'amount' => $order_amount,
        ]);

        // ручное добавление средств на счет для проведения тестирования
        $customer->account->update([
            'balance' => $customer_balance,
        ]);

        $this->assertEqualsWithDelta(0, $restaurant->account->balance, 0.01, 'Restautant account\'s balance not equals to 0');

        try {
            $customer->billPay($bill);
            $this->fail('PaymentException was not thrown');
        } catch (PaymentException $e) {
            $this->assertEquals('Not enough money on balance', $e->getMessage());
        }

        $restaurant->refresh();
        $this->assertEqualsWithDelta(0, $restaurant->account->balance, 0.01);
        $customer->refresh();
        $this->assertEqualsWithDelta($customer_balance, $customer->account->balance, 0.01);

        $bill->refresh();
        $this->assertEquals(Bill::STATUS_NOT_PAID, $bill->status);

        $this->assertDatabaseMissing('payment_history', [
            'from_type' => 'account',
            'from_id' => $customer->account->id,
            'to_type' => 'account',
            'to_id' => $restaurant->account->id,
            'for_type' => 'bill',
            'for_id' => $bill->id,
            'amount' => $order_amount,
        ]);
    }
}
