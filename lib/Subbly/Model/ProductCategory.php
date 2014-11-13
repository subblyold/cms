<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableInterface;

class ProductCategory extends Model implements ModelInterface, SortableInterface
{
    use Concerns\SubblyModel;
    use Sortable;

    protected $table = 'product_categories';

    /**
     * Fields
     */
    protected $visible = array('name', 'position', 'created_at', 'updated_at');

    protected $fillable = array('name');

    public $sortable = array(
        'order_column_name' => 'position',
    );

    /**
     * Validations
     */
    protected $rules = array(
        'product_id' => 'required|exists:products,id',
        'name'       => 'required',
    );

    /**
     *
     */
    protected function performInsert(\Illuminate\Database\Eloquent\Builder $query, array $options)
    {
        $this->attributes['uid'] = md5(uniqid(mt_rand(), true));

        parent::performInsert($query, $options);
    }

    /**
     * Relashionship
     */
    public function parent()
    {
        return $this->belongsTo('Subbly\\Model\\ProductCategory', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Subbly\\Model\\ProductCategory', 'parent_id');
    }

    public function product()
    {
        return $this->belongsTo('Subbly\\Model\\Product', 'product_id');
    }
}
