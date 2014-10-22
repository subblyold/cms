<?php

namespace Subbly\Model;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableInterface;

class Product extends Model implements SortableInterface
{
    use Sortable;

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

    public $sortable = array(
        'order_column_name' => 'position',
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
