<?php

namespace Modules\RestaurantManagers\Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Restaurants\Entities\Restaurant;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тестирование регистрации пользователя
     */
    public function testRegisterRestaurantManager()
    {
        /** @var Restaurant $restaurant */
        $restaurant = factory(Restaurant::class)->create();

        $response = $this->postJson('api/restaurant-manager/auth/register', [
            'surname' => 'Surname',
            'first_name' => 'Firstname',
            'middle_name' => 'Middlename',
            'phone_number' => '+79991234455',
            'email' => 'registeruser@mail.local',
            'password' => '123456789',
            'password_confirmation' => '123456789',
            'restaurant_id' => $restaurant->id,
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
    public function testRegisterRestaurantManagerWithWrongData()
    {
        $response = $this->postJson('api/restaurant-manager/auth/register', [
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
