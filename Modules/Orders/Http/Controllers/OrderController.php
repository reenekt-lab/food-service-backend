<?php

namespace Modules\Orders\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Orders\Entities\Order;
use Modules\Orders\Http\Requests\OrderCreateRequest;
use Modules\Orders\Http\Requests\OrderUpdateRequest;
use Modules\Orders\Transformers\OrderCollection;
use Modules\Orders\Transformers\Order as OrderResource;
use Throwable;

class OrderController extends Controller
{
    public function __construct()
    {
        // TODO secure policy later
//        auth()->shouldUse('api');
//        $this->authorizeResource(Order::class);
    }

    /**
     * Display a listing of the resource.
     * @return OrderCollection
     */
    public function index()
    {
        $resource = Order::paginate();
        return new OrderCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param OrderCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(OrderCreateRequest $request)
    {
        $order = new Order;
        $order->fill($request->all());
        $order->saveOrFail();
        return response()->json([
            'message' => __('orders::order.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     * @param OrderUpdateRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update($request->all());
        return response()->json([
            'message' => __('orders::order.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Order $order
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'message' => __('orders::order.deleted'),
        ]);
    }
}
