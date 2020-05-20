<?php

namespace Modules\Restaurants\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\Restaurants\Entities\Food;
use Modules\Restaurants\Http\Requests\FoodCreateRequest;
use Modules\Restaurants\Http\Requests\FoodUpdateRequest;
use Modules\Restaurants\Transformers\FoodCollection;
use Modules\Restaurants\Transformers\Food as FoodResource;
use Throwable;

class FoodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
        $this->authorizeResource(Food::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return FoodCollection
     */
    public function index()
    {
        $resource = Food::paginate();
        return new FoodCollection($resource);
    }

    /**
     * Возвращает список блюд принадлежащих ресторану менеджера.
     *
     * @return FoodCollection
     * @throws AuthorizationException
     */
    public function listByRestaurant()
    {
        $this->authorize('viewAny', Food::class);

        if (auth('api')->check()) {
            $resource = Food::paginate();
        } elseif (auth('restaurant_manager')->check()) {
            /** @var RestaurantManager $user */
            $user = auth('restaurant_manager')->user();
            $resource = Food::whereRestaurantId($user->restaurant_id)->paginate();
        } else {
            $resource = Food::paginate();
        }

        return new FoodCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FoodCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(FoodCreateRequest $request)
    {
        $food = new Food;
        $food->fill($request->all());
        $food->saveOrFail();
        return response()->json([
            'message' => __('restaurants::food.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param Food $food
     * @return FoodResource
     */
    public function show(Food $food)
    {
        return new FoodResource($food);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FoodUpdateRequest $request
     * @param Food $food
     * @return JsonResponse
     */
    public function update(FoodUpdateRequest $request, Food $food)
    {
        $food->update($request->all());
        return response()->json([
            'message' => __('restaurants::food.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Food $food
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Food $food)
    {
        $food->delete();
        return response()->json([
            'message' => __('restaurants::food.deleted'),
        ]);
    }
}
