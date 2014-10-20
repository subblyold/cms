<?php

namespace Subbly\Model;

class OrderProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_products';

    /**
     * Relashionship
     */
    public function order()
    {
        return $this->belongsTo('Subbly\\Model\\Order', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo('Subbly\\Model\\Product', 'product_id');
    }
}
