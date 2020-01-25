<?php

namespace Modules\Customers\Tests\Feature;

use App\Models\User;
use Modules\Customers\Entities\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerManagementAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Клиент не может получить доступ к списку всех клиентов.
     * Доступ к списку клиентов доступен только администрации сервиса.
     *
     * @return void
     */
    public function testCustomerCantAccessAdminIndex()
    {
        $customer = factory(Customer::class)->create();

        $response = $this->actingAs($customer, 'customer')->getJson('/api/customer');

        $response
            ->assertForbidden();
    }

    /**
     * Администрация Сервиса может получить доступ к списку всех клиентов.
     *
     * @return void
     */
    public function testAdminUserCanAccessAdminIndex()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/customer');

        $response
            ->assertOk();
    }
}
