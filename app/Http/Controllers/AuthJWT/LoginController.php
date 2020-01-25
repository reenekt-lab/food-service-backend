<?php

namespace App\Http\Controllers\AuthJWT;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends JWTBaseController
{
    /** @var string $using_middleware Посредник, используемый в данном контроллере */
    protected $using_middleware = 'auth:api';

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = null;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware($this->using_middleware)->except('login');
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
        $token = auth($this->guard)->attempt($credentials);

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
        auth($this->guard)->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
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
