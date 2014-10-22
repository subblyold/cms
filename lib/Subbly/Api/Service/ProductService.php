<?php

namespace Subbly\Api\Service;

use Subbly\Model\Product;

class ProductService extends Service
{
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
     * @return Illuminate\Database\Eloquent\Collection
     *
     * @api
     */
    public function all()
    {
        return Product::all();
    }

    /**
     * Find a Product by $id
     *
     * @example
     *     Subbly::api('subbly.product')->find('sku');
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
     * Create a new Product
     *
     * @example
     *     $product = Subbly\Model\Product;
     *     Subbly::api('subbly.product')->create($product);
     *
     *     Subbly::api('subbly.product')->create(array(
     *         'first_name' => 'John',
     *         'last_name'  => 'Snow',
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
        $product = null;

        if (is_array($product)) {
            $product = new Product($product);
        }

        $event = $this->fireEvent('creating', array($product));

        if ($product instanceof Product) {
            $this->saveModel($product);
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
     *     Subbly::api('subbly.product')->update($user_uid, array(
     *         'firstname' => 'John',
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

        $event = $this->fireEvent('updating', array($product));

        if ($product instanceof Product)
        {
            $this->saveModel($product);
        }
        else {
            throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
                'Subbly\\Model\\Product',
                $this->name()
            ));
        }

        $event = $this->fireEvent('updated', array($product));

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
