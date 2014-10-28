<?php

namespace Subbly\Api\Service;

use Subbly\Model\Collection;
use Subbly\Model\Product;

class ProductService extends Service
{
    protected $modelClass = 'Subbly\\Model\\Product';

    /**
     * Return an empty model
     *
     * @return Subbly\Model\Product
     *
     * @api
     */
    public function newProduct()
    {
        return new Product();
    }

    /**
     * Get all Product
     *
     * @param array $options
     *
     * @return Illuminate\Database\Eloquent\Collection
     *
     * @api
     */
    public function all(array $options = array())
    {
        $query = $this->newQuery($options);

        return new Collection($query);
    }

    /**
     * Find a Product by $id
     *
     * @example
     *     $product = Subbly::api('subbly.product')->find('sku');
     *
     * @param string $sku
     *
     * @return Product
     *
     * @api
     */
    public function find($sku, array $options = array())
    {
        $options = array_replace(array(
            'with_images'     => false,
            'with_options'    => false,
            'with_categories' => false,
        ), $options);

        $query = Product::query();
        $query->where('sku', '=', $sku);

        if ($options['with_images'] === true) {
            $query->with('images');
        }

        if ($options['with_options'] === true) {
            $query->with('options');
        }

        if ($options['with_categories'] === true) {
            $query->with('categories');
        }

        return $query->firstOrFail();
    }

    /**
     * Search a Product by options
     *
     * @example
     *     $products = Subbly::api('subbly.product')->searchBy(array(
     *         'sku'  => 'p123',
     *         'name' => 'awesome product',
     *     ));
     *
     * @param array $options
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function searchBy(array $options)
    {
        $options = array_replace(array(
            'global' => null,
            'sku'    => null,
            'name'   => null,
        ));

        $query = Product::query();

        if ($options['global']) {
        }
        if ($options['sku']) {
            $query->where('sku', 'LIKE', "%{$options['sku']}%");
        }
        if ($options['name']) {
            $query->where('name', 'LIKE', "%{$options['name']}%");
        }

        return new Collection($query);
    }

    /**
     * Create a new Product
     *
     * @example
     *     $product = Subbly\Model\Product;
     *     Subbly::api('subbly.product')->create($product);
     *
     *     Subbly::api('subbly.product')->create(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param Product|array $product
     *
     * @return Product
     *
     * @api
     */
    public function create($product)
    {
        if (is_array($product)) {
            $product = new Product($product);
        }

        if ($this->fireEvent('creating', array($product)) === false) return false;

        if ($product instanceof Product) {
            $product->setCaller($this);
            $product->save();
        }
        else {
            throw new Exception(sprintf(Exception::CANT_CREATE_MODEL,
                'Subbly\\Model\\Product',
                $this->name()
            ));
        }

        $event = $this->fireEvent('created', array($product));

        return $product;
    }

    /**
     * Update a Product
     *
     * @example
     *     $product = [Subbly\Model\Product instance];
     *     Subbly::api('subbly.product')->update($product);
     *
     *     Subbly::api('subbly.product')->update($product_sku, array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param Product|integer
     * @param array|null
     *
     * @return Product
     *
     * @api
     */
    public function update()
    {
        $args    = func_get_args();
        $product = null;

        if (count($args) == 1 && $args[0] instanceof Product) {
            $product = $args[0];
        }
        else if (count($args) == 2 && !empty($args[0]) && is_array($args[1]))
        {
            $product = $this->find($args[0]);
            $product->fill($args[1]);
        }

        if ($this->fireEvent('updating', array($product)) === false) return false;

        if ($product instanceof Product)
        {
            $product->setCaller($this);
            $product->save();
        }
        else {
            throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
                'Subbly\\Model\\Product',
                $this->name()
            ));
        }

        $this->fireEvent('updated', array($product));

        return $product;
    }

    /**
     * Service name
     */
    public function name()
    {
        return 'subbly.product';
    }
}
