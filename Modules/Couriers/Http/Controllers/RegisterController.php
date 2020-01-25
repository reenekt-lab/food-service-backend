<?php

namespace Modules\Couriers\Http\Controllers;

use App\Http\Controllers\AuthJWT\RegisterController as RegisterBaseController;
use Modules\Couriers\Entities\Courier;

class RegisterController extends RegisterBaseController
{
    /** @var Courier|null $model Класс модели Eloquent, используемый для регистрации пользователя */
    protected $modelClass = Courier::class;

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = 'courier';
}
