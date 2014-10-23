<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model implements ModelInterface
{
    use Concerns\SubblyModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * Relashionship
     */
    public function user()
    {
        return $this->belongsTo('Subbly\\Model\\User', 'user_id');
    }

    public function product()
    {
        return $this->hasMany('Subbly\\Model\\OrderProduct');
    }

    public function shippingAddress()
    {
        return $this->belongsTo('Subbly\\Model\\OrderAddress', 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo('Subbly\\Model\\OrderAddress', 'billing_address_id');
    }
}
