<?php

namespace App\Http\Controllers\AuthJWT;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends JWTBaseController
{
    /** @var Model|null $model Класс модели Eloquent, используемый для регистрации пользователя */
    protected $modelClass = User::class;

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = null;

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

        $user = $this->createUser($request);

//        /** Send email to user. @see \Illuminate\Auth\Listeners\SendEmailVerificationNotification */
//        event(new Registered($user));
        $this->dispatchUserCreatedEvent($user);

        $token = auth($this->guard)->login($user);

        return $this->respondWithToken($token);
    }

    /**
     * Creates new user in application (database)
     *
     * @param Request $request
     * @return Authenticatable
     */
    public function createUser(Request $request): Authenticatable
    {
        $attributes = $request->all();
        $attributes['password'] = Hash::make($attributes['password']);

        return $this->modelClass::create($attributes);
    }

    /**
     * Dispatch events after user created
     *
     * @param Authenticatable $user
     */
    public function dispatchUserCreatedEvent($user): void
    {
        /** Send email to user. @see \Illuminate\Auth\Listeners\SendEmailVerificationNotification */
        event(new Registered($user));
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
