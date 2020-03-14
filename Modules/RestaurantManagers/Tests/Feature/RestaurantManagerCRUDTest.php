<?php

namespace Modules\RestaurantManagers\Tests\Feature;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\RestaurantManagers\Transformers\RestaurantManager as RestaurantManagerResource;
use Modules\RestaurantManagers\Transformers\RestaurantManagerCollection;
use Modules\Restaurants\Entities\Restaurant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestaurantManagerCRUDTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Получение всех записей в БД
     */
    public function testIndex()
    {
        factory(RestaurantManager::class)->create();

        $resource = new RestaurantManagerCollection(RestaurantManager::paginate());

        $response = $this->getJson('/api/restaurant-manager');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ])
            ->assertJsonFragment($resource->jsonSerialize()[0]); // [0] fixes $resource array key error. Need to think about this problem
    }

    /**
     * Тест сохранения записи в БД
     */
    public function testStore()
    {
        $faker = $this->faker('ru_RU');

        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        $password = $faker->password(8, 10);
        $data = [
            'surname' => 'Тестовый',
            'first_name' => 'Тест',
            'middle_name' => 'Тестович',
            'phone_number' => $faker->e164PhoneNumber,
            'email' => $faker->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'restaurant_id' => $restaurant->id,
        ];

        $response = $this->postJson('/api/restaurant-manager', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => __('restaurantmanagers::restaurant_manager.created'),
            ]);
    }

    /**
     * Тест сохранения записи с  неправильными данными в БД
     */
    public function testStoreWrong()
    {
        $faker = $this->faker('ru_RU');

        /** @var RestaurantManager $existing_restaurant_manager */
        $existing_restaurant_manager = factory(RestaurantManager::class)->create();

        $password = $faker->password(2, 3);
        $data = [
            'surname' => 'Тестовый',
            'middle_name' => 123,
            'phone_number' => $faker->e164PhoneNumber,
            'email' => $existing_restaurant_manager->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/restaurant-manager', $data);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'first_name',
                    'middle_name',
                    'email',
                    'password',
                ]
            ]);
    }

    /**
     * Получение одной записи из БД
     */
    public function testShow()
    {
        /** @var RestaurantManager $restaurantManager */
        $restaurantManager = factory(RestaurantManager::class)->create();

        $resource = new RestaurantManagerResource($restaurantManager);

        // FIXME убрать костыль с исправлением типа bool на int, возвращаемый в ответе
        $resource->resource->is_admin = 0;

        $response = $this->getJson("/api/restaurant-manager/{$restaurantManager->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->jsonSerialize());
    }

    /**
     * Получение одной записи из БД
     */
    public function testShowNotFound()
    {
        $response = $this->getJson("/api/restaurant-manager/999");

        $response
            ->assertStatus(404);
    }

    /**
     * Изменение записи в БД
     */
    public function testUpdate()
    {
        $faker = $this->faker('ru_RU');
        $old_phone_number = $faker->e164PhoneNumber;
        $new_phone_number = $faker->e164PhoneNumber;

        /** @var RestaurantManager $restaurantManager */
        $restaurantManager = factory(RestaurantManager::class)->create([
            'surname' => 'Тестовый',
            'first_name' => 'Тест',
            'middle_name' => 'Тестович',
            'phone_number' => $old_phone_number,
        ]);

        $data = [
            'surname' => 'Тестовый2',
            'first_name' => 'Тест2',
            'middle_name' => 'Тестович2',
            'phone_number' => $new_phone_number,
            'email' => $restaurantManager->email,
        ];

        $response = $this->putJson("/api/restaurant-manager/{$restaurantManager->id}", $data);

        // Update restaurant manager's data
        /** @var RestaurantManager $restaurantManager */
        $restaurantManager = RestaurantManager::find($restaurantManager->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('restaurantmanagers::restaurant_manager.updated'),
            ]);

        $this->assertEquals('Тестовый2', $restaurantManager->surname);
        $this->assertEquals('Тест2', $restaurantManager->first_name);
        $this->assertEquals('Тестович2', $restaurantManager->middle_name);
        $this->assertEquals($new_phone_number, $restaurantManager->phone_number);
        $this->assertNotEquals('Тестовый', $restaurantManager->surname);
        $this->assertNotEquals('Тест', $restaurantManager->first_name);
        $this->assertNotEquals('Тестович', $restaurantManager->middle_name);
        $this->assertNotEquals($old_phone_number, $restaurantManager->phone_number);
    }

    /**
     * Изменение записи в БД с неправильными значениями
     */
    public function testUpdateWrong()
    {
        /** @var RestaurantManager $restaurantManager */
        $restaurantManager = factory(RestaurantManager::class)->create([
            'surname' => 'Тестовый',
            'first_name' => 'Тест',
            'middle_name' => 'Тестович',
        ]);

        $data = [
            'surname' => 'Тестовый2',
            'first_name' => '',
            'middle_name' => 123,
        ];

        $response = $this->putJson("/api/restaurant-manager/{$restaurantManager->id}", $data);

        // Update restaurant manager's data
        $restaurantManager = RestaurantManager::find($restaurantManager->id);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'first_name',
                    'middle_name',
                ]
            ]);

        $this->assertNotEquals('', $restaurantManager->first_name);
        $this->assertNotEquals(123, $restaurantManager->middle_name);
        $this->assertEquals('Тест', $restaurantManager->first_name);
        $this->assertEquals('Тестович', $restaurantManager->middle_name);
    }

    /**
     * Удаление записи из БД
     */
    public function testDelete()
    {
        /** @var RestaurantManager $restaurantManager */
        $restaurantManager = factory(RestaurantManager::class)->create();

        $response = $this->deleteJson("/api/restaurant-manager/{$restaurantManager->id}");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('restaurantmanagers::restaurant_manager.deleted'),
            ]);
        $this->assertSoftDeleted($restaurantManager);
        $this->assertDatabaseHas('restaurant_managers', ['id' => $restaurantManager->id]);
    }

    /**
     * Удаление записи из БД
     */
    public function testDeleteNotFound()
    {
        // Проверка отсутствия в БД записи
        $this->assertDatabaseMissing('restaurant_managers', ['id' => 999]);

        $response = $this->deleteJson("/api/restaurant-manager/999");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(404);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // все запросы будет идти от имени администратора сервиса
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
    }
}
