<?php

namespace Modules\Couriers\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
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
        $this->middleware('auth:api,restaurant_manager')->except('index', 'show');
        $this->authorizeResource(Courier::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return CourierCollection
     */
    public function index()
    {
        $resource = Courier::with('restaurant')->paginate();
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
        $courier->fill($request->all());
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
        $courier->update($request->all());
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
