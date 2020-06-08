<?php

namespace Modules\Restaurants\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\Restaurants\Entities\Food;
use Modules\Restaurants\Http\Requests\FoodCreateRequest;
use Modules\Restaurants\Http\Requests\FoodUpdateRequest;
use Modules\Restaurants\Transformers\FoodCollection;
use Modules\Restaurants\Transformers\Food as FoodResource;
use Nwidart\Modules\Facades\Module;
use Throwable;

class FoodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api,restaurant_manager')->except('index', 'show', 'listByRestaurant');
        $this->authorizeResource(Food::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return FoodCollection
     */
    public function index(Request $request)
    {
        $restaurant_id = $request->query('restaurant');
        $resource_query = Food::with(['restaurant', 'categories', 'tags']);
        if ($restaurant_id !== null) {
            $resource_query->where('restaurant_id', $restaurant_id);
        }
        $resource = $resource_query->paginate();
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

        if (Module::isEnabled('FoodCatalog') && $request->has('categories')) {
            $food->categories()->sync($request->input('categories'));
        }
        if (Module::isEnabled('FoodCatalog') && $request->has('tags')) {
            $food->tags()->sync($request->input('tags'));
        }

        if ($request->hasFile('main_image')) {
            $food->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

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
        return new FoodResource($food->load(['restaurant', 'categories', 'tags']));
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

        if (Module::isEnabled('FoodCatalog') && $request->has('categories')) {
            $food->categories()->sync($request->input('categories'));
        }
        if (Module::isEnabled('FoodCatalog') && $request->has('tags')) {
            $food->tags()->sync($request->input('tags'));
        }

        if ($request->hasFile('main_image')) {
            $food->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

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
