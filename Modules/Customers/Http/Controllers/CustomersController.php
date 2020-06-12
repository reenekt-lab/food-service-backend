<?php

namespace Modules\Customers\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Customers\Entities\Customer;
use Modules\Customers\Http\Requests\CustomerCreateRequest;
use Modules\Customers\Http\Requests\CustomerUpdateRequest;
use Modules\Customers\Transformers\Customer as CustomerResource;
use Modules\Customers\Transformers\CustomerCollection;
use Throwable;

class CustomersController extends Controller
{
    public function __construct()
    {
        auth()->shouldUse('api'); // strange, but works. FIXME
        $this->middleware('auth:api,customer')->except('index', 'show');
//        $this->authorizeResource(Customer::class); // todo later
    }

    /**
     * Display a listing of the resource.
     *
     * @return CustomerCollection
     */
    public function index()
    {
        $resource = Customer::paginate();
        return new CustomerCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerCreateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CustomerCreateRequest $request)
    {
        $customer = new Customer;
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $customer->fill($request->all());
        $customer->saveOrFail();
        return response()->json([
            'message' => __('customers::customers.created'),
        ], 201);
    }

    /**
     * Show the specified resource.
     *
     * @param Customer $customer
     * @return CustomerResource
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerUpdateRequest $request
     * @param Customer $customer
     * @return JsonResponse
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $customer->update($data);
        return response()->json([
            'message' => __('customers::customers.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'message' => __('customers::customers.deleted'),
        ]);
    }
}
