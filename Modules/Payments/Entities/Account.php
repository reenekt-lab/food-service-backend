<?php

namespace Modules\Payments\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modules\Payments\Entities\Account
 *
 * @property int $id
 * @property string $number Номер счета
 * @property float $balance Баланс счета
 * @property string $currency Валюта
 * @property string $owner_type
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account newQuery()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Payments\Entities\Account onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Payments\Entities\Account withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Payments\Entities\Account withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Payments\Entities\PaymentHistory[] $paymentFrom
 * @property-read int|null $payment_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Payments\Entities\PaymentHistory[] $paymentTo
 * @property-read int|null $payment_to_count
 * @property-read \Modules\Payments\Entities\Account $owner
 */
class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'balance',
        'currency',
        'owner_id',
        'owner_type',
    ];

    /**
     * Владелец счета (Клиент, Ресторан или Компания-клиент)
     * @return MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Записи платежей, в которых денежные средства списались с текущего счета
     * @return MorphMany
     */
    public function paymentFrom()
    {
        return $this->morphMany(PaymentHistory::class, 'from');
    }

    /**
     * Записи платежей, в которых денежные средства пришли на текущий счет
     * @return MorphMany
     */
    public function paymentTo()
    {
        return $this->morphMany(PaymentHistory::class, 'to');
    }
}
