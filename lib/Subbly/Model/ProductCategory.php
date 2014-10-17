<?php

namespace Subbly\Model;

class ProductCategory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_categories';

    public function parent()
    {
        return $this->belongsTo('Subbly\\Model\\ProductCategory', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Subbly\\Model\\ProductCategory', 'parent_id');
    }
}
