<?php

namespace Subbly\Model;

class OrderAddress extends Model
{
    use Trait\AddressTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_addresses';
}
