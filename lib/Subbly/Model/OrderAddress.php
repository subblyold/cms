<?php

namespace Subbly\Model;

class OrderAddress extends Model
{
    use Concerns\AddressTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_addresses';

    /**
     * Relashionship
     */
    public function order()
    {
        return $this->hasOne('Subbly\\Model\\Order');
    }
}
