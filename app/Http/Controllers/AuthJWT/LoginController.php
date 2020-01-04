<?php

namespace App\Http\Controllers\AuthJWT;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends JWTBaseController
{
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except('login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // Validates login request
        $this->getLoginValidator($request->all())->validate();

        $credentials = $request->only([$this->username(), 'password']);

        // Authentication attempt
        $token = auth()->attempt($credentials);

        if ($token) {
            return $this->respondWithToken($token);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Returns validator for login request
     *
     * @param array $data Login request's data
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function getLoginValidator(array $data)
    {
        $rules = [
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        // TODO messages
        $messages = [
//            $this->username() . '.required' => '',
//            $this->username() . '.string' => '',
//            'password.required' => '',
//            'password.string' => '',
        ];

        return Validator::make($data, $rules, $messages);
    }
}
