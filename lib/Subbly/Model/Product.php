<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableInterface;

class Product extends Model  implements ModelInterface, SortableInterface
{
    use Sortable;
    use Concerns\SubblyModel;

    protected $table = 'products';

    /**
     * Fields
     */
    protected $visible = array('position', 'status', 'sku', 'name', 'description', 'price', 'sale_price', 'quantity', 'images', 'options', 'categories', 'created_at', 'updated_at');

    protected $fillable = array('status', 'sku', 'name', 'description', 'price', 'sale_price', 'quantity');

    /**
     * Validation rules
     */
    protected $rules = array(
        'status' => 'required',
        'name'   => 'required',
        'sku'    => 'required|unique:products,sku,{{self_id}}',
        'price'  => 'required|regex:/^\d+(\.\d{1,2})?$/',
    );

    protected $defaultValues = array(
        'status' => self::STATUS_DRAFT,
    );

    public $sortable = array(
        'order_column_name' => 'position',
    );

    const STATUS_DRAFT      = 'draft';
    const STATUS_ACTIVE     = 'active';
    const STATUS_HIDDEN     = 'hidden';
    const STATUS_SOLDOUT    = 'sold_out';
    const STATUS_COMINGSOON = 'coming_soon';

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


    public function getPriceAttribute()
    {
        return (double) $this->attributes['price'];
    }
    public function getSalePriceAttribute()
    {
        return $this->attributes['sale_price'] === null
            ? null
            : (double) $this->attributes['sale_price']
        ;
    }
}
