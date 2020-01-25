<?php

namespace Modules\Restaurants\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Restaurants\Entities\Restaurant;
use Modules\Restaurants\Http\Requests\RestaurantCreateRequest;
use Modules\Restaurants\Http\Requests\RestaurantUpdateRequest;
use Modules\Restaurants\Transformers\RestaurantCollection;
use Modules\Restaurants\Transformers\Restaurant as RestaurantResource;
use Throwable;

class RestaurantsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Restaurant::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return RestaurantCollection
     */
    public function index()
    {
        $resource = Restaurant::paginate();
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
        return new RestaurantResource($restaurant);
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
        $restaurant->delete();

        // Возможно в будущем будет заменено на http code 204
        return response()->json([
            'message' => __('restaurants::restaurants.deleted'),
        ]);
    }
}
