<?php

namespace Modules\Customers\Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тестирование регистрации пользователя
     */
    public function testRegisterCustomer()
    {
        $response = $this->postJson('api/customer/auth/register', [
            'surname' => 'Surname',
            'first_name' => 'Firstname',
            'middle_name' => 'Middlename',
            'phone_number' => '+79991234455',
            'email' => 'registeruser@mail.local',
            'password' => '123456789',
            'password_confirmation' => '123456789',
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
     * Тестирование регистрации пользователя с неправильными данными
     */
    public function testRegisterCustomerWithWrongData()
    {
        $response = $this->postJson('api/customer/auth/register', [
            'surname' => 'Surname',
            //'first_name' => 'Firstname',
            'middle_name' => 'Middlename',
            'phone_number' => '+79991234455',
            'email' => 'registeruser@mail',
            'password' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    '*' => []
                ],
            ]);
    }
}
