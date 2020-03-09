<?php

namespace Modules\RestaurantManagers\Http\Controllers;

use App\Http\Controllers\AuthJWT\RegisterController as RegisterBaseController;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\RestaurantManagers\Entities\RestaurantManager;
use Modules\RestaurantManagers\Events\RestaurantManagerRegistered;

class RegisterController extends RegisterBaseController
{
    /** @var RestaurantManager|null $model Класс модели Eloquent, используемый для регистрации пользователя */
    protected $modelClass = RestaurantManager::class;

    /** @var string|null $guard Guard используемый для авторизации */
    protected $guard = 'restaurant_manager';

    /**
     * Dispatch events after user created
     *
     * @param RestaurantManager|Authenticatable $user
     */
    public function dispatchUserCreatedEvent($user): void
    {
        /** Send email to user. @see \Illuminate\Auth\Listeners\SendEmailVerificationNotification */
        event(new RestaurantManagerRegistered($user));
    }
}
