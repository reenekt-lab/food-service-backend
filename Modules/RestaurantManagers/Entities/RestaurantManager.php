<?php

namespace Modules\RestaurantManagers\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Modules\Restaurants\Entities\Restaurant;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\RestaurantManagers\Entities\RestaurantManager
 *
 * @property int $id
 * @property string $surname Фамилия
 * @property string $first_name Имя
 * @property string|null $middle_name Отчество
 * @property string $phone_number Номер телефона
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager newQuery()
 * @method static \Illuminate\Database\Query\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager withoutTrashed()
 * @mixin \Eloquent
 * @property int $restaurant_id ID ресторана менеджера
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereRestaurantId($value)
 * @property-read \Modules\Restaurants\Entities\Restaurant $restaurant
 * @property int $is_admin Является ли менеджер владельцем (администратором) ресторана
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\RestaurantManagers\Entities\RestaurantManager whereIsAdmin($value)
 */
class RestaurantManager extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'surname',
        'first_name',
        'middle_name',
        'phone_number',
        'email',
        'password',
        'restaurant_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
}
