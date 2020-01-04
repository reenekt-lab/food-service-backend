<?php

namespace App\Http\Controllers\AuthJWT;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends JWTBaseController
{
    /**
     * Register new user
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        // Validates register request
        $this->getRegisterValidator($request->all())->validate();

        $user = User::create([
            'surname' => $request->input('surname'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        /** Send email to user. @see \Illuminate\Auth\Listeners\SendEmailVerificationNotification */
        event(new Registered($user));

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    /**
     * Returns validator for register request
     *
     * @param array $data Login request's data
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function getRegisterValidator(array $data)
    {
        $rules = [
            'surname' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // TODO messages
        $messages = [];

        return Validator::make($data, $rules, $messages);
    }
}
