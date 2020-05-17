<?php

namespace Modules\Restaurants\Tests\Feature;

use App\Models\User;
use Modules\Couriers\Entities\Courier;
use Modules\Customers\Entities\Customer;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\Restaurants\Entities\Restaurant;
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
    public function testCustomerCantAccessAdminIndex()
    {
        $this->markTestIncomplete(
            'Feature\'s test must be updated / remade'
        );

        $customer = factory(Customer::class)->create();
        $restaurant = factory(Restaurant::class)->create();

        $response = $this->actingAs($customer, 'customer')->getJson("/api/restaurants/{$restaurant->id}");

        $response
            ->assertForbidden();
    }

    /**
     * Клиент не может получить доступ к списку всех клиентов.
     * Доступ к списку клиентов доступен только администрации сервиса.
     *
     * @return void
     */
    public function testCourierCantAccessAdminIndex()
    {
        $this->markTestIncomplete(
            'Feature\'s test must be updated / remade'
        );

        $courier = factory(Courier::class)->create();
        $restaurant = factory(Restaurant::class)->create();

        $response = $this->actingAs($courier, 'courier')->getJson("/api/restaurants/{$restaurant->id}");

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
        $restaurant = factory(Restaurant::class)->create();

        $response = $this->actingAs($user, 'api')->getJson("/api/restaurants/{$restaurant->id}");

        $response
            ->assertOk();
    }

    /**
     * Менеджер ресторана может получить доступ к своему ресторану (которым он управляет)
     *
     * @return void
     */
    public function testRestaurantManagerCanAccessOwnAdminIndex()
    {
        /** @var RestaurantManager $restaurantManager */
        $restaurantManager = factory(RestaurantManager::class)->create();
        $restaurant = $restaurantManager->restaurant;

        $response = $this->actingAs($restaurantManager, 'restaurant_manager')->getJson("/api/restaurants/{$restaurant->id}");

        $response
            ->assertOk();
    }

    /**
     * Менеджер ресторана не может получить доступ к ресторанам, которыми он не управляет
     *
     * @return void
     */
    public function testRestaurantManagerCantAccessOtherAdminIndex()
    {
        $this->markTestIncomplete(
            'Feature\'s test must be updated / remade'
        );

        /** @var RestaurantManager $restaurantManager1 */
        $restaurantManager1 = factory(RestaurantManager::class)->create();
        $restaurant1 = $restaurantManager1->restaurant;

        /** @var RestaurantManager $restaurantManager2 */
        $restaurantManager2 = factory(RestaurantManager::class)->create();
        $restaurant2 = $restaurantManager2->restaurant;

        $response1 = $this->actingAs($restaurantManager1, 'restaurant_manager')->getJson("/api/restaurants/{$restaurant2->id}");
        $response2 = $this->actingAs($restaurantManager2, 'restaurant_manager')->getJson("/api/restaurants/{$restaurant1->id}");

        $response1
            ->assertForbidden();
        $response2
            ->assertForbidden();
    }
}
