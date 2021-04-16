<?php

declare(strict_types = 1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @package App
 */
class Order extends Model
{
    public const STATUS_NEW = 1;
    public const STATUS_IN_PROCESS = 2;
    public const STATUS_PAYMENT_EXPECTED = 3;
    public const STATUS_COMPLETED = 4;
    public const STATUS_POSTPONED = 5;
    public const STATUS_PAYED = 6;
    public const STATUS_NEW_CREDIT = 7;
    public const STATUS_NEW_FREE_LOAN = 8;

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'notes',
        'ordered_product',
        'credit_data',
        'ordered_status',
        'ip_address',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'notes' => 'string',
        'ordered_product' => 'array',
        'credit_data' => 'array',
        'ordered_status' => 'int',
        'ip_address' => 'string',
    ];

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
