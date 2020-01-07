<?php

namespace Modules\Restaurants\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Restaurants\Entities\Food;
use Modules\Restaurants\Http\Requests\FoodCreateRequest;
use Modules\Restaurants\Http\Requests\FoodUpdateRequest;
use Modules\Restaurants\Transformers\FoodCollection;
use Modules\Restaurants\Transformers\Food as FoodResource;
use Throwable;

class FoodController extends Controller
{
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
            'message' => 'Food saved'
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
            'message' => 'Food updated'
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
            'message' => 'Food deleted'
        ]);
    }
}
