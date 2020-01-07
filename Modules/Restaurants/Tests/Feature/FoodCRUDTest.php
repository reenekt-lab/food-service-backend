<?php

namespace Modules\Restaurants\Tests\Feature;

use Modules\Restaurants\Entities\Food;
use Modules\Restaurants\Entities\Restaurant;
use Modules\Restaurants\Transformers\Food as FoodResource;
use Modules\Restaurants\Transformers\FoodCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Получение всех записей в БД
     */
    public function testIndex()
    {
        factory(Food::class)->create();

        $resource = new FoodCollection(Food::paginate());

        $response = $this->getJson('/api/food');

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
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create([
            'name' => 'My Beauty Restaurant',
        ]);

        $data = [
            'name' => 'Test Food',
            'description' => 'Example Food for tests',
            'cost' => 250,
            'restaurant_id' => $restaurant->id,
        ];

        $response = $this->postJson('/api/food', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => __('restaurants::food.created'),
            ]);
    }

    /**
     * Тест сохранения записи с  неправильными данными в БД
     */
    public function testStoreWrong()
    {
        $data = [
            'description' => 'Example Food for tests',
        ];

        $response = $this->postJson('/api/food', $data);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'cost',
                    'restaurant_id',
                ]
            ]);
    }

    /**
     * Получение одной записи из БД
     */
    public function testShow()
    {
        /** @var Food $food */
        $food = factory(Food::class)->create([
            'name' => 'My Beauty Food',
        ]);

        $resource = new FoodResource($food);

        $response = $this->getJson("/api/food/{$food->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->jsonSerialize());
    }

    /**
     * Получение одной записи из БД
     */
    public function testShowNotFound()
    {
        $response = $this->getJson("/api/food/1");

        $response
            ->assertStatus(404);
    }

    /**
     * Изменение записи в БД
     */
    public function testUpdate()
    {
        /** @var Food $food */
        $food = factory(Food::class)->create([
            'name' => 'My First Test Food',
        ]);

        $data = [
            'name' => 'My Beauty Food with PHP-Sauce :D',
        ];

        $response = $this->putJson("/api/food/{$food->id}", $data);

        // Update restaurant's data
        $food = Food::find($food->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('restaurants::food.updated'),
            ]);

        $this->assertEquals('My Beauty Food with PHP-Sauce :D', $food->name);
        $this->assertNotEquals('My First Test Food', $food->name);
    }

    /**
     * Изменение записи в БД с неправильными значениями
     */
    public function testUpdateWrong()
    {
        /** @var Food $food */
        $food = factory(Food::class)->create([
            'name' => 'My First Test Food',
        ]);

        $data = [
            'name' => '',
            'description' => '',
        ];

        $response = $this->putJson("/api/food/{$food->id}", $data);

        // Update restaurant's data
        $food = Food::find($food->id);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'description',
                ]
            ]);

        $this->assertNotEquals('', $food->name);
        $this->assertEquals('My First Test Food', $food->name);
    }

    /**
     * Удаление записи из БД
     */
    public function testDelete()
    {
        /** @var Food $food */
        $food = factory(Food::class)->create();

        $response = $this->deleteJson("/api/food/{$food->id}");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('restaurants::food.deleted'),
            ]);
        $this->assertDeleted($food);
    }

    /**
     * Удаление записи из БД
     */
    public function testDeleteNotFound()
    {
        // Проверка отсутствия в БД записи
        $this->assertDatabaseMissing('restaurants', ['id' => 1]);

        $response = $this->deleteJson("/api/food/1");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(404);
    }
}
