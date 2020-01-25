<?php

namespace Modules\RestaurantManagers\Tests\Feature;

use App\Models\User;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestaurantManagerManagementAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Клиент не может получить доступ к списку всех клиентов.
     * Доступ к списку клиентов доступен только администрации сервиса.
     *
     * @return void
     */
    public function testRestaurantManagerCantAccessAdminIndex()
    {
        $restaurantManager = factory(RestaurantManager::class)->create();

        $response = $this->actingAs($restaurantManager, 'restaurant_manager')->getJson('/api/restaurant-manager');

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

        $response = $this->actingAs($user, 'api')->getJson('/api/restaurant-manager');

        $response
            ->assertOk();
    }
}
