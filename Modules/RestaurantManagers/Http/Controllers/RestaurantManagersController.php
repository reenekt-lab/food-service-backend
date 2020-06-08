<?php

namespace Modules\RestaurantManagers\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\RestaurantManagers\Http\Requests\RestaurantManagerCreateRequest;
use Modules\RestaurantManagers\Http\Requests\RestaurantManagerUpdateRequest;
use Modules\RestaurantManagers\Transformers\RestaurantManager as RestaurantManagerResource;
use Modules\RestaurantManagers\Transformers\RestaurantManagerCollection;
use Throwable;

class RestaurantManagersController extends Controller
{
    public function __construct()
    {
        auth()->shouldUse('api'); // strange, but works. FIXME
        $this->middleware('auth:api')->except('index', 'show');
//        $this->authorizeResource(RestaurantManager::class); // todo later
    }

    /**
     * Display a listing of the resource.
     *
     * @return RestaurantManagerCollection
     */
    public function index()
    {
        $resource = RestaurantManager::with('restaurant')->paginate();
        return new RestaurantManagerCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RestaurantManagerCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(RestaurantManagerCreateRequest $request)
    {
        $restaurant_manager = new RestaurantManager;
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $restaurant_manager->fill($data);
        $restaurant_manager->saveOrFail();
        return response()->json([
            'message' => __('restaurantmanagers::restaurant_manager.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param RestaurantManager $restaurant_manager
     * @return RestaurantManagerResource
     */
    public function show(RestaurantManager $restaurant_manager)
    {
        return new RestaurantManagerResource($restaurant_manager->load('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RestaurantManagerUpdateRequest $request
     * @param RestaurantManager $restaurant_manager
     * @return JsonResponse
     */
    public function update(RestaurantManagerUpdateRequest $request, RestaurantManager $restaurant_manager)
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $restaurant_manager->update($data);
        return response()->json([
            'message' => __('restaurantmanagers::restaurant_manager.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RestaurantManager $restaurant_manager
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(RestaurantManager $restaurant_manager)
    {
        $restaurant_manager->delete();
        return response()->json([
            'message' => __('restaurantmanagers::restaurant_manager.deleted'),
        ]);
    }
}
