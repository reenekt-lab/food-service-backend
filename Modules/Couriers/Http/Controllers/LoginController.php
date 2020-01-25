<?php

namespace Modules\Couriers\Http\Controllers;

use App\Http\Controllers\AuthJWT\LoginController as LoginBaseController;

class LoginController extends LoginBaseController
{
    /** @var string $using_middleware Посредник, используемый в данном контроллере */
    protected $using_middleware = 'auth:courier';

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = 'courier';
}
