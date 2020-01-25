<?php

namespace Modules\Customers\Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Customers\Entities\Customer;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тестирование аутентификации пользователя
     */
    public function testLogCustomerIn()
    {
        $response = $this->postJson('api/customer/auth/login', [
            'email' => 'loginuser@mail.local',
            'password' => '123456789'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    /**
     * Тестирование аутентификации пользователя с неправильными данными
     */
    public function testNotLogWrongCustomerIn()
    {
        $response = $this->postJson('api/customer/auth/login', [
            'email' => 'wronguser@mail.local',
            'password' => '0123456789'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    '*' => []
                ],
            ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Создание пользователя в БД
        factory(Customer::class)->create([
            'email' => 'loginuser@mail.local',
            'password' => Hash::make('123456789'),
        ]);
    }
}
