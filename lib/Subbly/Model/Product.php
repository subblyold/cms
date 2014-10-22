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
     * The attributes visible from the model's JSON form.
     *
     * @var array
     */
    protected $visible = array('position', 'status', 'sku', 'name', 'description', 'price', 'sale_price', 'quantity', 'images', 'options', 'categories');

    protected $fillable = array('status', 'sku', 'name', 'description', 'price', 'sale_price', 'quantity');

    protected $rules = array(
        'status' => 'required',
        'name'   => 'required',
        'sku'    => 'required',
    );

    protected $defaultAttributes = array(
        'status' => self::STATUS_DRAFT,
    );

    public $sortable = array(
        'order_column_name' => 'position',
    );

    const STATUS_DRAFT = 'draft';

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
