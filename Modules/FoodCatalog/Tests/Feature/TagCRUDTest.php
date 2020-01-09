<?php

namespace Modules\FoodCatalog\Tests\Feature;

use Modules\FoodCatalog\Entities\Tag;
use Modules\FoodCatalog\Transformers\Tag as TagResource;
use Modules\FoodCatalog\Transformers\TagCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagCRUDTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Получение всех записей в БД
     */
    public function testIndex()
    {
        factory(Tag::class)->create();

        $resource = new TagCollection(Tag::paginate());

        $response = $this->getJson('/api/tags');

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
            'name' => 'Test Tag',
            'description' => 'Example Tag for tests',
        ];

        $response = $this->postJson('/api/tags', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => __('foodcatalog::tag.created'),
            ]);
    }

    /**
     * Тест сохранения записи с  неправильными данными в БД
     */
    public function testStoreWrong()
    {
        $data = [
            'description' => 'Example Tag for tests',
        ];

        $response = $this->postJson('/api/tags', $data);

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
        /** @var Tag $tag */
        $tag = factory(Tag::class)->create([
            'name' => 'My Beauty Tag',
        ]);

        $resource = new TagResource($tag);

        $response = $this->getJson("/api/tags/{$tag->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->jsonSerialize());
    }

    /**
     * Получение одной записи из БД
     */
    public function testShowNotFound()
    {
        $response = $this->getJson("/api/tags/1");

        $response
            ->assertStatus(404);
    }

    /**
     * Изменение записи в БД
     */
    public function testUpdate()
    {
        /** @var Tag $tag */
        $tag = factory(Tag::class)->create([
            'name' => 'My First Test Tag',
        ]);

        $data = [
            'name' => 'My Beauty Tag',
        ];

        $response = $this->putJson("/api/tags/{$tag->id}", $data);

        // Update tag's data
        $tag = Tag::find($tag->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::tag.updated'),
            ]);

        $this->assertEquals('My Beauty Tag', $tag->name);
        $this->assertNotEquals('My First Test Tag', $tag->name);
    }

    /**
     * Изменение записи в БД с неправильными значениями
     */
    public function testUpdateWrong()
    {
        /** @var Tag $tag */
        $tag = factory(Tag::class)->create([
            'name' => 'My First Test Tag',
        ]);

        $data = [
            'name' => '',
        ];

        $response = $this->putJson("/api/tags/{$tag->id}", $data);

        // Update tag's data
        $tag = Tag::find($tag->id);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ]
            ]);

        $this->assertNotEquals('', $tag->name);
        $this->assertEquals('My First Test Tag', $tag->name);
    }

    /**
     * Удаление записи из БД
     */
    public function testDelete()
    {
        /** @var Tag $tag */
        $tag = factory(Tag::class)->create();

        $response = $this->deleteJson("/api/tags/{$tag->id}");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::tag.deleted'),
            ]);
        $this->assertDeleted($tag);
    }

    /**
     * Удаление записи из БД
     */
    public function testDeleteNotFound()
    {
        // Проверка отсутствия в БД записи
        $this->assertDatabaseMissing('tags', ['id' => 1]);

        $response = $this->deleteJson("/api/tags/1");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(404);
    }
}
