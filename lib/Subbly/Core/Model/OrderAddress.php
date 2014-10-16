<?php

namespace Subbly\Core\Model;

class OrderAddress extends Model
{
    use Traits\AddressTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_addresses';
}
