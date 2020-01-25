<?php

namespace Modules\Couriers\Tests\Feature;

use App\Models\User;
use Modules\Couriers\Entities\Courier;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourierManagementAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Клиент не может получить доступ к списку всех клиентов.
     * Доступ к списку клиентов доступен только администрации сервиса.
     *
     * @return void
     */
    public function testCourierCantAccessAdminIndex()
    {
        $courier = factory(Courier::class)->create();

        $response = $this->actingAs($courier, 'courier')->getJson('/api/courier');

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

        $response = $this->actingAs($user, 'api')->getJson('/api/courier');

        $response
            ->assertOk();
    }
}
