<?php

namespace Modules\Payments\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Customers\Entities\Customer;
use Modules\Orders\Entities\Order;
use Modules\Restaurants\Entities\Restaurant;

/**
 * Modules\Payments\Entities\Bill
 *
 * @property int $id
 * @property int $customer_id ID покупателя
 * @property int $restaurant_id ID ресторана, которому нужно заплатить за заказ
 * @property int $order_id ID заказа
 * @property mixed $amount Сумма к оплате
 * @property string $status Статус оплаты
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Customers\Entities\Customer $customer
 * @property-read \Modules\Orders\Entities\Order $order
 * @property-read \Modules\Restaurants\Entities\Restaurant $restaurant
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill notFullyPaid()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill notPaid()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill paid()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Bill whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Payments\Entities\PaymentHistory[] $paymentFor
 * @property-read int|null $payment_for_count
 */
class Bill extends Model
{
    /** @var string Статус: счет не оплачен. На этом статусе счет ожидает оплаты от клиента, но денежные средства еще не были внесены */
    public const STATUS_NOT_PAID = 'not_paid';

    /** @var string Статус: счет полностью оплечен */
    public const STATUS_PAID = 'paid';

    /** @var string Статус: счет частично оплечен. Этот статус применим только для корпоративных клиентов, которые могут оплачивать заказ используя два счета */
    public const STATUS_NOT_FULLY_PAID = 'not_fully_paid';

    protected $fillable = [
        'customer_id',
        'restaurant_id',
        'order_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Клиент (покупатель)
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Ресторан (продавец)
     * @return BelongsTo
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Заказ (объект оплаты)
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Записи платежей, в которых целью платежа была оплата по текущему выставленному счету
     * @return MorphMany
     */
    public function paymentFor()
    {
        return $this->morphMany(PaymentHistory::class, 'to');
    }

    public function scopeNotPaid(Builder $query)
    {
        return $query->where('status', self::STATUS_NOT_PAID);
    }

    public function scopePaid(Builder $query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeNotFullyPaid(Builder $query)
    {
        return $query->where('status', self::STATUS_NOT_FULLY_PAID);
    }
}
