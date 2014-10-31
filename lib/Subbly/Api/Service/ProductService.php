<?php

namespace Subbly\Api\Service;

use Subbly\Model\Collection;
use Subbly\Model\Product;

class ProductService extends Service
{
    protected $modelClass = 'Subbly\\Model\\Product';

    protected $includableRelationships = array('images', 'options', 'categories');

    /**
     * Return an empty model
     *
     * @return \Subbly\Model\Product
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
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @api
     */
    public function all(array $options = array())
    {
        $query = $this->newCollectionQuery($options);

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
     * @return \Subbly\Model\Product
     *
     * @api
     */
    public function find($sku, array $options = array())
    {
        $options = array_replace(array(
            'includes' => array('images', 'categories', 'options'),
        ), $options);

        $query = $this->newQuery();
        $query->where('sku', '=', $sku);

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
     *     // OR
     *     $products = Subbly::api('subbly.product')->searchBy('p123');
     *
     * @param array|string  $searchQuery    Search params
     * @param array         $options        Query options
     * @param string        $statementsType Type of statement null|or|and (default is null)
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function searchBy($searchQuery, array $options = array(), $statementsType = null)
    {
        $query = $this->newSearchQuery($searchQuery, array(
            'sku',
            'name',
            'description',
        ), $statementsType, $options);

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
     * @param \Subbly\Model\Product|array $product
     *
     * @return \Subbly\Model\Product
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
     * @param \Subbly\Model\Product|integer
     * @param array|null
     *
     * @return \Subbly\Model\Product
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
