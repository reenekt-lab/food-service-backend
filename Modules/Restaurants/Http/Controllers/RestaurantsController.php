<?php

namespace Modules\Restaurants\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Restaurants\Entities\Restaurant;
use Modules\Restaurants\Events\RestaurantCreated;
use Modules\Restaurants\Http\Requests\RestaurantCreateRequest;
use Modules\Restaurants\Http\Requests\RestaurantUpdateRequest;
use Modules\Restaurants\Transformers\RestaurantCollection;
use Modules\Restaurants\Transformers\Restaurant as RestaurantResource;
use Throwable;

class RestaurantsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api,restaurant_manager')->except('index', 'show');
        $this->authorizeResource(Restaurant::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return RestaurantCollection
     */
    public function index(Request $request)
    {
        $resource_query = Restaurant::with('common_categories');
        $common_category_id = $request->query('common_category');
        if ($common_category_id !== null) {
            $resource_query->whereHas('common_categories', function (Builder $query) use ($common_category_id) {
                $query->where('id', $common_category_id);
            });
        }
        $resource = $resource_query->paginate();
        return new RestaurantCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RestaurantCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(RestaurantCreateRequest $request)
    {
        $restaurant = new Restaurant;
        $restaurant->fill($request->all());
        $restaurant->saveOrFail();

        if ($request->hasFile('main_image')) {
            $restaurant->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        $common_categories = $request->input('categories', []);
        if (!empty($common_categories)) {
            $restaurant->common_categories()->attach($common_categories);
        }

        event(new RestaurantCreated($restaurant));

        return response()->json([
            'message' => __('restaurants::restaurants.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param Restaurant $restaurant
     * @return RestaurantResource
     */
    public function show(Restaurant $restaurant)
    {
        return new RestaurantResource($restaurant->load('common_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RestaurantUpdateRequest $request
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function update(RestaurantUpdateRequest $request, Restaurant $restaurant)
    {
        $restaurant->update($request->all());

        if ($request->hasFile('main_image')) {
            $restaurant->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        $common_categories = $request->input('categories', []);
        if (!empty($common_categories)) {
            $restaurant->common_categories()->sync($common_categories);
        }

        return response()->json([
            'message' => __('restaurants::restaurants.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Restaurant $restaurant
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->common_categories()->detach();
        $restaurant->delete();

        // Возможно в будущем будет заменено на http code 204
        return response()->json([
            'message' => __('restaurants::restaurants.deleted'),
        ]);
    }
}
