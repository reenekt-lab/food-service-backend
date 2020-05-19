<?php

namespace Modules\Couriers\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Couriers\Entities\Courier
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
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier newQuery()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Couriers\Entities\Courier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Couriers\Entities\Courier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Couriers\Entities\Courier withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Couriers\Entities\Courier withoutTrashed()
 * @mixin \Eloquent
 */
class Courier extends Authenticatable
{
    use otifiable, SoftDeletes;

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
}
