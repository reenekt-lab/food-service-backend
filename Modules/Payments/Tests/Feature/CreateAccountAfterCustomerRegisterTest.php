<?php

namespace Modules\Payments\Tests\Feature;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Modules\Customers\Entities\Customer;
use Modules\Payments\Entities\Account;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAccountAfterCustomerRegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тестирование создания счета после регистрации нового клиента
     */
    public function testCreateAccountForCustomer()
    {
        $response = $this->postJson('api/customer/auth/register', [
            'surname' => 'Account',
            'first_name' => 'Testes',
            'middle_name' => 'Customer',
            'phone_number' => '+79991234455',
            'email' => 'registeruser@mail.local',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ]);

        /** @var Customer $customer */
        $customer = Customer::where([
            'surname' => 'Account',
            'first_name' => 'Testes',
            'middle_name' => 'Customer',
            'phone_number' => '+79991234455',
            'email' => 'registeruser@mail.local',
        ])->first();

        $this->assertGreaterThan(0, $customer->account()->count());
        $this->assertEquals('customer', $customer->account->owner_type);
        $this->assertEquals($customer->id, $customer->account->owner_id);
        $this->assertEquals(0, $customer->account->balance);
    }
}
