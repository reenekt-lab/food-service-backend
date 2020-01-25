<?php

namespace Modules\Customers\Tests\Feature;

use App\Models\User;
use Modules\Customers\Entities\Customer;
use Modules\Customers\Transformers\Customer as CustomerResource;
use Modules\Customers\Transformers\CustomerCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerCRUDTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Получение всех записей в БД
     */
    public function testIndex()
    {
        factory(Customer::class)->create();

        $resource = new CustomerCollection(Customer::paginate());

        $response = $this->getJson('/api/customer');

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

        $response = $this->postJson('/api/customer', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => __('customers::customers.created'),
            ]);
    }

    /**
     * Тест сохранения записи с  неправильными данными в БД
     */
    public function testStoreWrong()
    {
        $faker = $this->faker('ru_RU');

        /** @var Customer $existing_customer */
        $existing_customer = factory(Customer::class)->create();

        $password = $faker->password(2, 3);
        $data = [
            'surname' => 'Тестовый',
            'middle_name' => 123,
            'phone_number' => $faker->e164PhoneNumber,
            'email' => $existing_customer->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/customer', $data);

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
        /** @var Customer $customer */
        $customer = factory(Customer::class)->create();

        $resource = new CustomerResource($customer);

        $response = $this->getJson("/api/customer/{$customer->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resource->jsonSerialize());
    }

    /**
     * Получение одной записи из БД
     */
    public function testShowNotFound()
    {
        $response = $this->getJson("/api/customer/999");

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

        /** @var Customer $customer */
        $customer = factory(Customer::class)->create([
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
            'email' => $customer->email,
        ];

        $response = $this->putJson("/api/customer/{$customer->id}", $data);

        // Update customer's data
        /** @var Customer $customer */
        $customer = Customer::find($customer->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('customers::customers.updated'),
            ]);

        $this->assertEquals('Тестовый2', $customer->surname);
        $this->assertEquals('Тест2', $customer->first_name);
        $this->assertEquals('Тестович2', $customer->middle_name);
        $this->assertEquals($new_phone_number, $customer->phone_number);
        $this->assertNotEquals('Тестовый', $customer->surname);
        $this->assertNotEquals('Тест', $customer->first_name);
        $this->assertNotEquals('Тестович', $customer->middle_name);
        $this->assertNotEquals($old_phone_number, $customer->phone_number);
    }

    /**
     * Изменение записи в БД с неправильными значениями
     */
    public function testUpdateWrong()
    {
        /** @var Customer $customer */
        $customer = factory(Customer::class)->create([
            'surname' => 'Тестовый',
            'first_name' => 'Тест',
            'middle_name' => 'Тестович',
        ]);

        $data = [
            'surname' => 'Тестовый2',
            'first_name' => '',
            'middle_name' => 123,
        ];

        $response = $this->putJson("/api/customer/{$customer->id}", $data);

        // Update customer's data
        $customer = Customer::find($customer->id);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'first_name',
                    'middle_name',
                ]
            ]);

        $this->assertNotEquals('', $customer->first_name);
        $this->assertNotEquals(123, $customer->middle_name);
        $this->assertEquals('Тест', $customer->first_name);
        $this->assertEquals('Тестович', $customer->middle_name);
    }

    /**
     * Удаление записи из БД
     */
    public function testDelete()
    {
        /** @var Customer $customer */
        $customer = factory(Customer::class)->create();

        $response = $this->deleteJson("/api/customer/{$customer->id}");

        // Возможно в будущем будет заменено на http code 204
        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('customers::customers.deleted'),
            ]);
        $this->assertSoftDeleted($customer);
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    /**
     * Удаление записи из БД
     */
    public function testDeleteNotFound()
    {
        // Проверка отсутствия в БД записи
        $this->assertDatabaseMissing('customers', ['id' => 999]);

        $response = $this->deleteJson("/api/customer/999");

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
