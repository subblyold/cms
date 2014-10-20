<?php

namespace Subbly\Model;

class ProductOption extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_options';

    /**
     * Relashionship
     */
    public function product()
    {
        return $this->belongsTo('Subbly\\Model\\Product', 'product_id');
    }
}
