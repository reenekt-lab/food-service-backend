<?php


namespace Modules\Customers\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Customers\Entities\Customer;

class RegisterCustomerController
{
    /**
     * Returns current authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'surname' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $result = (new Customer($data))->save();
        return response()->json([
            'result' => $result,
            'message' => $result ? 'user registered' : 'user registration error'
        ], $result ? 200 : 500);
    }
}
