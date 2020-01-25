<?php

namespace Modules\Restaurants\Tests\Feature;

use App\Models\User;
use Modules\Restaurants\Entities\Restaurant;
use Modules\Restaurants\Transformers\Restaurant as RestaurantResource;
use Modules\Restaurants\Transformers\RestaurantCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestaurantsCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Получение всех записей в БД
     */
    public function testIndex()
    {
        factory(Restaurant::class)->create();

        $resource = new RestaurantCollection(Restaurant::paginate());

        $response = $this->getJson('/api/restaurants');

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
        $data = [
            'name' => 'Test Restaurant',
            'description' => 'Example Restaurant for tests',
            'address' => 'This computer, testing database',
        ];

        $response = $this->postJson('/api/restaurants', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => __('restaurants::restaurants.created'),
            ]);
    }

    /**
     * Тест сохранения записи с  неправильными данными в БД
     */
    public function testStoreWrong()
    {
        $data = [
            'description' => 'Example Restaurant for tests',
        ];

        $response = $this->postJson('/api/restaurants', $data);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'address',
                ]
            ]);
    }

    /**
     * Получение одной записи из БД
     */
    public function testShow()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create([
            'name' => 'My Beauty Restaurant',
        ]);

        $resource = new RestaurantResource($restaurant);

        $response = $this->getJson("/api/restaurants/{$restaurant->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->jsonSerialize());
    }

    /**
     * Получение одной записи из БД
     */
    public function testShowNotFound()
    {
        $response = $this->getJson("/api/restaurants/1");

        $response
            ->assertStatus(404);
    }

    /**
     * Изменение записи в БД
     */
    public function testUpdate()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create([
            'name' => 'My First Test Restaurant',
        ]);

        $data = [
            'name' => 'My Beauty Restaurant',
        ];

        $response = $this->putJson("/api/restaurants/{$restaurant->id}", $data);

        // Update restaurant's data
        $restaurant = Restaurant::find($restaurant->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('restaurants::restaurants.updated'),
            ]);

        $this->assertEquals('My Beauty Restaurant', $restaurant->name);
        $this->assertNotEquals('My First Test Restaurant', $restaurant->name);
    }

    /**
     * Изменение записи в БД с неправильными значениями
     */
    public function testUpdateWrong()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create([
            'name' => 'My First Test Restaurant',
        ]);

        $data = [
            'name' => '',
        ];

        $response = $this->putJson("/api/restaurants/{$restaurant->id}", $data);

        // Update restaurant's data
        $restaurant = Restaurant::find($restaurant->id);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ]
            ]);

        $this->assertNotEquals('', $restaurant->name);
        $this->assertEquals('My First Test Restaurant', $restaurant->name);
    }

    /**
     * Удаление записи из БД
     */
    public function testDelete()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        $response = $this->deleteJson("/api/restaurants/{$restaurant->id}");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('restaurants::restaurants.deleted'),
            ]);
        $this->assertDeleted($restaurant);
    }

    /**
     * Удаление записи из БД
     */
    public function testDeleteNotFound()
    {
        // Проверка отсутствия в БД записи
        $this->assertDatabaseMissing('restaurants', ['id' => 999]);

        $response = $this->deleteJson("/api/restaurants/999");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(404);
    }

    protected function setUp(): void
    {
        parent::setUp();
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
    }
}
