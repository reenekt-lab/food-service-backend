<?php

namespace Modules\Payments\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Modules\Payments\Entities\PaymentHistory
 *
 * @property int $id
 * @property string $from_type
 * @property int $from_id
 * @property string $to_type
 * @property int $to_id
 * @property string $for_type
 * @property int $for_id
 * @property float $amount Сумма платежа
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereForId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereForType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereFromType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\PaymentHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Modules\Payments\Entities\PaymentHistory $for
 * @property-read \Modules\Payments\Entities\PaymentHistory $from
 * @property-read \Modules\Payments\Entities\PaymentHistory $to
 */
class PaymentHistory extends Model
{
    protected $table = 'payment_history';

    protected $fillable = [
        'from_id',
        'from_type',
        'to_id',
        'to_type',
        'for_id',
        'for_type',
        'amount',
    ];

    /**
     * Кто совершил платеж
     * @return MorphTo
     */
    public function from()
    {
        return $this->morphTo();
    }

    /**
     * Кому перечислены денежные средства
     * @return MorphTo
     */
    public function to()
    {
        return $this->morphTo();
    }

    /**
     * Цель платежа (На что был совершен платеж)
     * Платеж может быть совершен для оплаты выставленного счета или чего-то иного
     * @return MorphTo
     */
    public function for()
    {
        return $this->morphTo();
    }
}
