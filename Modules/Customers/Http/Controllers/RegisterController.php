<?php

namespace Modules\Customers\Http\Controllers;

use App\Http\Controllers\AuthJWT\RegisterController as RegisterBaseController;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Customers\Entities\Customer;
use Modules\Customers\Events\CustomerRegistered;

class RegisterController extends RegisterBaseController
{
    /** @var Customer|null $model Класс модели Eloquent, используемый для регистрации пользователя */
    protected $modelClass = Customer::class;

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = 'customer';

    /**
     * Dispatch events after user created
     *
     * @param Customer|Authenticatable $user
     */
    public function dispatchUserCreatedEvent($user): void
    {
        event(new CustomerRegistered($user));
    }
}
