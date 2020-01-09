<?php

namespace Modules\FoodCatalog\Tests\Feature;

use Modules\FoodCatalog\Entities\Category;
use Modules\FoodCatalog\Transformers\Category as CategoryResource;
use Modules\FoodCatalog\Transformers\CategoryCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Получение всех записей в БД
     */
    public function testIndex()
    {
        factory(Category::class)->create();

        $resource = new CategoryCollection(Category::paginate());

        $response = $this->getJson('/api/categories');

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
        /** @var Category $parent */
        $parent = factory(Category::class)->create([
            'name' => 'My Beauty Restaurant',
        ]);

        $data = [
            'name' => 'Test Category',
            'description' => 'Example Category for tests',
            'parent_id' => $parent->id,
        ];

        $response = $this->postJson('/api/categories', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => __('foodcatalog::category.created'),
            ]);
    }

    /**
     * Тест сохранения записи с  неправильными данными в БД
     */
    public function testStoreWrong()
    {
        $data = [
            'description' => 'Example Category for tests',
        ];

        $response = $this->postJson('/api/categories', $data);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ]
            ]);
    }

    /**
     * Получение одной записи из БД
     */
    public function testShow()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'name' => 'My Beauty Category',
        ]);

        $resource = new CategoryResource($category);

        $response = $this->getJson("/api/categories/{$category->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->jsonSerialize());
    }

    /**
     * Получение одной записи из БД
     */
    public function testShowNotFound()
    {
        $response = $this->getJson("/api/categories/999");

        $response
            ->assertStatus(404);
    }

    /**
     * Изменение записи в БД
     */
    public function testUpdate()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'name' => 'My First Test Category',
        ]);

        $data = [
            'name' => 'My Beauty Category with PHP-Sauce :D',
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        // Update restaurant's data
        $category = Category::find($category->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::category.updated'),
            ]);

        $this->assertEquals('My Beauty Category with PHP-Sauce :D', $category->name);
        $this->assertNotEquals('My First Test Category', $category->name);
    }

    /**
     * Изменение записи в БД с неправильными значениями
     */
    public function testUpdateWrong()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create([
            'name' => 'My First Test Category',
        ]);

        $data = [
            'name' => '',
            'description' => '',
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        // Update restaurant's data
        $category = Category::find($category->id);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ]
            ]);

        $this->assertNotEquals('', $category->name);
        $this->assertEquals('My First Test Category', $category->name);
    }

    /**
     * Удаление записи из БД
     */
    public function testDelete()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::category.deleted'),
            ]);
        $this->assertDeleted($category);
    }

    /**
     * Удаление записи из БД
     */
    public function testDeleteNotFound()
    {
        // Проверка отсутствия в БД записи
        $this->assertDatabaseMissing('restaurants', ['id' => 999]);

        $response = $this->deleteJson("/api/categories/999");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(404);
    }
}
