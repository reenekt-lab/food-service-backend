<?php

namespace Modules\RestaurantManagers\Http\Controllers;

use App\Http\Controllers\AuthJWT\RegisterController as RegisterBaseController;
use Modules\RestaurantManagers\Entities\RestaurantManager;

class RegisterController extends RegisterBaseController
{
    /** @var RestaurantManager|null $model Класс модели Eloquent, используемый для регистрации пользователя */
    protected $modelClass = RestaurantManager::class;

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = 'restaurant_manager';
}
