<?php

namespace Subbly\Model;

class OrderAddress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Relashionship
     */
    public function images()
    {
        return $this->hasMany('Subbly\\Model\\ProductImage');
    }

    public function options()
    {
        return $this->hasMany('Subbly\\Model\\ProductOption');
    }

    public function categories()
    {
        return $this->hasMany('Subbly\\Model\\ProductCategory');
    }
}
