<?php

namespace Modules\Customers\Http\Controllers;

use App\Http\Controllers\AuthJWT\RegisterController as RegisterBaseController;
use Modules\Customers\Entities\Customer;

class RegisterController extends RegisterBaseController
{
    /** @var Customer|null $model Класс модели Eloquent, используемый для регистрации пользователя */
    protected $modelClass = Customer::class;

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = 'customer';
}
