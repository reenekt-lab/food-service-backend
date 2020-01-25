<?php

namespace Modules\Restaurants\Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Couriers\Entities\Courier;
use Modules\Customers\Entities\Customer;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\Restaurants\Entities\Food;
use Modules\Restaurants\Entities\Restaurant;
use Modules\Restaurants\Transformers\Food as FoodResource;
use Modules\Restaurants\Transformers\FoodCollection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Exceptions\JWTException;

class FoodManagementAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @var array Матрица доступа */
    protected $accessMap = [
        User::class => [
            'index' => true,
            'store' => true,
            'show' => true,
            'update' => true,
            'destroy' => true,
        ],

        RestaurantManager::class => [
            'index' => true,
            'listByRestaurant' => 'callback:restaurantManagerCanSeeOnlyOwnFoodsList', /** @see restaurantManagerCanSeeOnlyOwnFoodsList */
            'store' => true,
            'show' => 'own only',
            'update' => 'own only',
            'destroy' => 'own only',
        ],

        Customer::class => [
            'index' => true,
            'store' => false,
            'show' => true,
            'update' => false,
            'destroy' => false,
        ],
    ];

    /** @var array Матрица доступа для неавторизованных пользователей */
    protected $guestAccessMap = [
        'index' => true,
        'store' => false,
        'show' => true,
        'update' => false,
        'destroy' => false,
    ];

    protected $guardMap = [
        User::class => 'api',
        RestaurantManager::class => 'restaurant_manager',
        Customer::class => 'customer',
    ];

    /**
     * Тест правильности доступа к определенным ресутсам для определенных пользователей
     */
    public function testFoodAccess()
    {
        foreach ($this->accessMap as $userType => $accessChecks) {
            // auth
            $user = factory($userType)->create();
            $this->actingAs($user, $this->guardMap[$userType]);

            // TODO вынести в TestCase (или куда-нибудь еще)
            // todo в переопределение функции \Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication::be
            // todo или \Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication::actingAs
            $token = auth($this->guardMap[$userType])->tokenById($user->id);
            auth($this->guardMap[$userType])->setToken($token);

            // run check callbacks
            foreach ($accessChecks as $accessCheck => $checkValue) {
                if (!Str::startsWith($checkValue, 'callback:')) {
                    $callback = $accessCheck . 'Check';
                    $this->$callback($user, $checkValue, $userType);
                } else {
                    $callback = Str::substr($checkValue, Str::length('callback:'));
                    $this->$callback($user, $checkValue, $userType);
                }
            }

            auth($this->guardMap[$userType])->logout();
        }
    }

    /**
     * Тест правильности доступа к определенным ресутсам для неавторизованных пользователей
     */
    public function testFoodAccessForGuests()
    {
        $this->logoutAllGuards();

        foreach ($this->guestAccessMap as $accessCheck => $checkValue) {
            if (!Str::startsWith($checkValue, 'callback:')) {
                $callback = $accessCheck . 'Check';
                $this->$callback(null, $checkValue, null);
            } else {
                $callback = Str::substr($checkValue, Str::length('callback:'));
                $this->$callback(null, $checkValue, null);
            }
        }
    }

    /**
     * Выход из всех возможных аккаунтов
     */
    protected function logoutAllGuards()
    {
        foreach ($this->guardMap as $userType => $guard) {
            try {
                auth($guard)->logout();
            } catch (JWTException $JWTException) {
                //
            }
        }
    }

    protected function indexCheck($user, $checkValue, $userType)
    {
        factory(Food::class)->create();

        $resource = new FoodCollection(Food::paginate());
        $response = $this->getJson('/api/food');

        switch ($checkValue) {
            case true:
                $response
                    ->assertOk()
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
                break;
            case false:
                $response
                    ->assertForbidden();
                break;
        }
    }

    protected function storeCheck($user, $checkValue, $userType)
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

        if ($userType == RestaurantManager::class && $user) {
            $foodAttributes['restaurant_id'] = $user->restaurant_id;
        }

        $response = $this->postJson('/api/food', $data);

        switch ($checkValue) {
            case true:
                $response
                    ->assertCreated()
                    ->assertJson([
                        'message' => __('restaurants::food.created'),
                    ]);
                break;
            case false:
                $response
                    ->assertForbidden();
                break;
        }
    }

    protected function showCheck($user, $checkValue, $userType)
    {
        $foodAttributes = [
            'name' => 'My Beauty Food',
        ];

        if ($userType == RestaurantManager::class && $user) {
            $foodAttributes['restaurant_id'] = $user->restaurant_id;
        }

        /** @var Food $food */
        $food = factory(Food::class)->create($foodAttributes);

        $resource = new FoodResource($food);

        $response = $this->getJson("/api/food/{$food->id}");

        switch ($checkValue) {
            case true:
                $response
                    ->assertOk()
                    ->assertJsonFragment($resource->jsonSerialize());
                break;
            case 'own only':
                if (!$user) {
                    $this->fail("It need user for \"own only\" check, {$user} given");
                }
                $response
                    ->assertOk()
                    ->assertJsonFragment($resource->jsonSerialize());

                /** @var Food $foodOther */
                $foodOther = factory(Food::class)->create([
                    'name' => 'My Beauty Food Other',
                ]);

                $message = 'Food\'s restaurant and User\'s restaurant must be not same in this check';
                $this->assertNotEquals($foodOther->restaurant_id, $user->restaurant_id, $message);

                $response = $this->getJson("/api/food/{$foodOther->id}");

                $response
                    ->assertForbidden();

                break;
            case false:
                $response
                    ->assertForbidden();
                break;
        }
    }

    protected function updateCheck($user, $checkValue, $userType)
    {
        $foodAttributes = [
            'name' => 'My First Test Food',
        ];

        if ($userType == RestaurantManager::class && $user) {
            $foodAttributes['restaurant_id'] = $user->restaurant_id;
        }

        /** @var Food $food */
        $food = factory(Food::class)->create($foodAttributes);

        $data = [
            'name' => 'My Beauty Food with PHP-Sauce :D',
        ];

        $response = $this->putJson("/api/food/{$food->id}", $data);

        // Update food's data
        $food = Food::find($food->id);

        switch ($checkValue) {
            case true:
                $response
                    ->assertStatus(200)
                    ->assertJson([
                        'message' => __('restaurants::food.updated'),
                    ]);

                $this->assertEquals('My Beauty Food with PHP-Sauce :D', $food->name);
                $this->assertNotEquals('My First Test Food', $food->name);
                break;
            case 'own only':
                if (!$user) {
                    $this->fail("It need user for \"own only\" check, {$user} given");
                }
                $response
                    ->assertStatus(200)
                    ->assertJson([
                        'message' => __('restaurants::food.updated'),
                    ]);

                $this->assertEquals('My Beauty Food with PHP-Sauce :D', $food->name);
                $this->assertNotEquals('My First Test Food', $food->name);

                /** @var Food $foodOther */
                $foodOther = factory(Food::class)->create([
                    'name' => 'Other First Test Food',
                ]);

                $message = 'Food\'s restaurant and User\'s restaurant must be not same in this check';
                $this->assertNotEquals($foodOther->restaurant_id, $user->restaurant_id, $message);

                $data = [
                    'name' => 'My Beauty Food with PHP-Sauce OTHER :D',
                ];

                $response = $this->putJson("/api/food/{$foodOther->id}", $data);

                // Update food's data
                $foodOther = Food::find($foodOther->id);
                $this->assertNotEquals('My Beauty Food with PHP-Sauce OTHER :D', $foodOther->name);
                $this->assertEquals('Other First Test Food', $foodOther->name);

                $response
                    ->assertForbidden();

                break;
            case false:
                $response
                    ->assertForbidden();
                break;
        }
    }

    protected function destroyCheck($user, $checkValue, $userType)
    {
        $foodAttributes = [];

        if ($userType == RestaurantManager::class && $user) {
            $foodAttributes['restaurant_id'] = $user->restaurant_id;
        }

        /** @var Food $food */
        $food = factory(Food::class)->create($foodAttributes);

        $response = $this->deleteJson("/api/food/{$food->id}");

        switch ($checkValue) {
            case true:
                // Возможно в будущем будет заменено на http code 204
                $response
                    ->assertStatus(200)
                    ->assertJson([
                        'message' => __('restaurants::food.deleted'),
                    ]);
                $this->assertDeleted($food);
                break;
            case 'own only':
                if (!$user) {
                    $this->fail("It need user for \"own only\" check, {$user} given");
                }
                // Возможно в будущем будет заменено на http code 204
                $response
                    ->assertStatus(200)
                    ->assertJson([
                        'message' => __('restaurants::food.deleted'),
                    ]);
                $this->assertDeleted($food);

                /** @var Food $foodOther */
                $foodOther = factory(Food::class)->create();

                $message = 'Food\'s restaurant and User\'s restaurant must be not same in this check';
                $this->assertNotEquals($foodOther->restaurant_id, $user->restaurant_id, $message);

                $response = $this->deleteJson("/api/food/{$foodOther->id}");

                // assert not deleted
                $this->assertDatabaseHas($foodOther->getTable(), [$foodOther->getKeyName() => $foodOther->getKey()], $foodOther->getConnectionName());

                $response
                    ->assertForbidden();

                break;
            case false:
                $response
                    ->assertForbidden();
                break;
        }
    }

    protected function restaurantManagerCanSeeOnlyOwnFoodsList($user, $checkValue, $userType)
    {
        $foodAttributes = [];
        $foodCount = 4;

        if ($userType == RestaurantManager::class) {
            $foodAttributes['restaurant_id'] = $user->restaurant_id;
        }

        factory(Food::class, $foodCount)->create($foodAttributes);
        factory(Food::class)->create(); // Food of other restaurant

        $resource = new FoodCollection(Food::whereRestaurantId($user->restaurant_id)->paginate());
        $resourceAll = new FoodCollection(Food::paginate());

        $response = $this->getJson("/api/restaurants/{$user->restaurant_id}/food");

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
            ->assertJsonFragment($resource->jsonSerialize()[0]) // [0] fixes $resource array key error. Need to think about this problem
            ->assertJsonPath('meta.total', 4)
            ->assertJsonMissing($resourceAll->jsonSerialize()[0]); // [0] fixes $resource array key error. Need to think about this problem
    }
}
