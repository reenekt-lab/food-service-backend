<?php

namespace Modules\Customers\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Modules\Customers\Entities\Customer
 *
 * @property int $id
 * @property string $surname Фамилия
 * @property string $first_name Имя
 * @property string|null $middle_name Отчество
 * @property string $phone_number Номер телефона
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Customers\Entities\Customer onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Customers\Entities\Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Customers\Entities\Customer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Customers\Entities\Customer withoutTrashed()
 */
class Customer extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

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

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
