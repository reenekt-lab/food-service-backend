<?php

namespace Modules\Couriers\Tests\Feature;

use App\Models\User;
use Modules\Couriers\Entities\Courier;
use Modules\Couriers\Transformers\Courier as CourierResource;
use Modules\Couriers\Transformers\CourierCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourierCRUDTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Получение всех записей в БД
     */
    public function testIndex()
    {
        factory(Courier::class)->create();

        $resource = new CourierCollection(Courier::paginate());

        $response = $this->getJson('/api/courier');

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

        $password = $faker->password(8, 10);
        $data = [
            'surname' => 'Тестовый',
            'first_name' => 'Тест',
            'middle_name' => 'Тестович',
            'phone_number' => $faker->e164PhoneNumber,
            'email' => $faker->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson('/api/courier', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => __('couriers::couriers.created'),
            ]);
    }

    /**
     * Тест сохранения записи с  неправильными данными в БД
     */
    public function testStoreWrong()
    {
        $faker = $this->faker('ru_RU');

        /** @var Courier $existing_courier */
        $existing_courier = factory(Courier::class)->create();

        $password = $faker->password(2, 3);
        $data = [
            'surname' => 'Тестовый',
            'middle_name' => 123,
            'phone_number' => $faker->e164PhoneNumber,
            'email' => $existing_courier->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/courier', $data);

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
        /** @var Courier $courier */
        $courier = factory(Courier::class)->create();

        $resource = new CourierResource($courier);

        $response = $this->getJson("/api/courier/{$courier->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->jsonSerialize());
    }

    /**
     * Получение одной записи из БД
     */
    public function testShowNotFound()
    {
        $response = $this->getJson("/api/courier/999");

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

        /** @var Courier $courier */
        $courier = factory(Courier::class)->create([
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
            'email' => $courier->email,
        ];

        $response = $this->putJson("/api/courier/{$courier->id}", $data);

        // Update courier's data
        /** @var Courier $courier */
        $courier = Courier::find($courier->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('couriers::couriers.updated'),
            ]);

        $this->assertEquals('Тестовый2', $courier->surname);
        $this->assertEquals('Тест2', $courier->first_name);
        $this->assertEquals('Тестович2', $courier->middle_name);
        $this->assertEquals($new_phone_number, $courier->phone_number);
        $this->assertNotEquals('Тестовый', $courier->surname);
        $this->assertNotEquals('Тест', $courier->first_name);
        $this->assertNotEquals('Тестович', $courier->middle_name);
        $this->assertNotEquals($old_phone_number, $courier->phone_number);
    }

    /**
     * Изменение записи в БД с неправильными значениями
     */
    public function testUpdateWrong()
    {
        /** @var Courier $courier */
        $courier = factory(Courier::class)->create([
            'surname' => 'Тестовый',
            'first_name' => 'Тест',
            'middle_name' => 'Тестович',
        ]);

        $data = [
            'surname' => 'Тестовый2',
            'first_name' => '',
            'middle_name' => 123,
        ];

        $response = $this->putJson("/api/courier/{$courier->id}", $data);

        // Update courier's data
        $courier = Courier::find($courier->id);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'first_name',
                    'middle_name',
                ]
            ]);

        $this->assertNotEquals('', $courier->first_name);
        $this->assertNotEquals(123, $courier->middle_name);
        $this->assertEquals('Тест', $courier->first_name);
        $this->assertEquals('Тестович', $courier->middle_name);
    }

    /**
     * Удаление записи из БД
     */
    public function testDelete()
    {
        /** @var Courier $courier */
        $courier = factory(Courier::class)->create();

        $response = $this->deleteJson("/api/courier/{$courier->id}");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('couriers::couriers.deleted'),
            ]);
        $this->assertSoftDeleted($courier);
        $this->assertDatabaseHas('couriers', ['id' => $courier->id]);
    }

    /**
     * Удаление записи из БД
     */
    public function testDeleteNotFound()
    {
        // Проверка отсутствия в БД записи
        $this->assertDatabaseMissing('couriers', ['id' => 999]);

        $response = $this->deleteJson("/api/courier/999");

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
