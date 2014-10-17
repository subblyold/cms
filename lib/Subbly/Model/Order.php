<?php

namespace Subbly\Model;

class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    public function user()
    {
        return $this->belongsTo('Subbly\\Model\\User');
    }
}
