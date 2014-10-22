<?php

namespace Subbly\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
    protected $defaultAttributes = array(
        'status' => self::STATUS_DRAFT,
    );

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
