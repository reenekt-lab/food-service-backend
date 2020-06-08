<?php

namespace Modules\Couriers\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Couriers\Entities\Courier;
use Modules\Couriers\Http\Requests\CourierCreateRequest;
use Modules\Couriers\Http\Requests\CourierUpdateRequest;
use Modules\Couriers\Transformers\Courier as CourierResource;
use Modules\Couriers\Transformers\CourierCollection;
use Throwable;

class CouriersController extends Controller
{
    public function __construct()
    {
        auth()->shouldUse('api'); // strange, but works. FIXME
        $this->middleware('auth:api,restaurant_manager,courier')->except('index', 'show');
//        $this->authorizeResource(Courier::class); // todo later
    }

    /**
     * Display a listing of the resource.
     *
     * @return CourierCollection
     */
    public function index(Request $request)
    {
        $restaurant_id = $request->query('restaurant');
        $resource_query = Courier::with('restaurant');
        if ($restaurant_id !== null) {
            if ($request->boolean('include_free_couriers')) {
                $resource_query->where('restaurant_id', $restaurant_id)->orWhereNull('restaurant_id');
            } else {
                $resource_query->where('restaurant_id', $restaurant_id);
            }
        }
        $resource = $resource_query->paginate();
        return new CourierCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourierCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CourierCreateRequest $request)
    {
        $courier = new Courier;
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $courier->fill($data);
        $courier->saveOrFail();
        return response()->json([
            'message' => __('couriers::couriers.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param Courier $courier
     * @return CourierResource
     */
    public function show(Courier $courier)
    {
        return new CourierResource($courier->load('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CourierUpdateRequest $request
     * @param Courier $courier
     * @return JsonResponse
     */
    public function update(CourierUpdateRequest $request, Courier $courier)
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $courier->update($data);
        return response()->json([
            'message' => __('couriers::couriers.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Courier $courier
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Courier $courier)
    {
        $courier->delete();
        return response()->json([
            'message' => __('couriers::couriers.deleted'),
        ]);
    }
}
