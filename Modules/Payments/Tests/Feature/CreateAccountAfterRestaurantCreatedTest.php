<?php

namespace Modules\Payments\Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Modules\Payments\Entities\Account;
use Modules\Restaurants\Entities\Restaurant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAccountAfterRestaurantCreatedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тестирование создания счета после создания нового ресторана
     */
    public function testCreateAccountForRestaurant()
    {
        $response = $this->postJson('api/restaurants', [
            'name' => 'My Restaurant',
            'description' => 'My Restaurant for testing account creating',
            'address' => 'This test',
        ]);

        $response->assertCreated();

        /** @var Restaurant $restaurant */
        $restaurant = Restaurant::where([
            'name' => 'My Restaurant',
            'description' => 'My Restaurant for testing account creating',
            'address' => 'This test',
        ])->first();

        $this->assertGreaterThan(0, $restaurant->account()->count());
        $this->assertEquals('restaurant', $restaurant->account->owner_type);
        $this->assertEquals($restaurant->id, $restaurant->account->owner_id);
        $this->assertEquals(0, $restaurant->account->balance);
    }

    protected function setUp(): void
    {
        parent::setUp();
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
    }
}
