<?php

namespace Subbly\Api\Service;

use Sentry;

use Subbly\Model\Collection;
use Subbly\Model\ProductCategory;
use Subbly\Model\Product;

class ProductCategoryService extends Service
{
    protected $modelClass = 'Subbly\\Model\\ProductCategory';

    protected $includableRelationships = array('product');

    /**
     * Return an empty model
     *
     * @return \Subbly\Model\ProductCategory
     *
     * @api
     */
    public function newProductCategory()
    {
        return new ProductCategory();
    }

    /**
     * Find a ProductCategory by $uid
     *
     * @example
     *     $productCategory = Subbly::api('subbly.product_category')->find($uid);
     *
     * @param string  $uid
     * @param array   $options
     *
     * @return \Subbly\Model\ProductCategory
     *
     * @api
     */
    public function find($uid, array $options = array())
    {
        $query = $this->newQuery($options);
        $query->where('uid', '=', $uid);

        return $query->firstOrFail();
    }

    /**
     * Find a ProductCategory by Product
     *
     * @example
     *     $productCategory = Subbly::api('subbly.product_category')->findByProduct($product);
     *
     * @param string|\Subbly\Model\Product  $product The Product model or the Product uid
     * @param array                         $options Some options
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function findByProduct($product, array $options = array())
    {
        if (!$product instanceof Product) {
            $product = $this->api('subbly.product')->find($product);
        }

        $query = $this->newCollectionQuery($options);
        $query->with(array('product' => function($query) use ($product) {
            $query->where('uid', '=', $product->uid);
        }));

        return new Collection($query);
    }

    /**
     * Create a new ProductCategory
     *
     * @example
     *     $product = Subbly\Model\ProductCategory;
     *     Subbly::api('subbly.product_category')->create($productCategory);
     *
     *     Subbly::api('subbly.product_category')->create(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param \Subbly\Model\ProductCategory|array  $productCategory
     * @param \Subbly\Model\Product|null           $product
     *
     * @return \Subbly\Model\ProductCategory
     *
     * @throws \Subbly\Api\Service\Exception
     *
     * @api
     */
    public function create($productCategory, $product)
    {
        if (!$product instanceof Product) {
            $product = $this->api('subbly.product')->find($product);
        }

        if (is_array($productCategory)) {
            $productCategory = new ProductCategory($productCategory);
        }

        if ($productCategory instanceof ProductCategory)
        {
            if ($this->fireEvent('creating', array($productCategory)) === false) return false;

            $productCategory->product()->associate($product);

            $productCategory->setCaller($this);
            $productCategory->save();

            $this->fireEvent('created', array($productCategory));

            $productCategory = $this->find($productCategory->uid);

            return $productCategory;
        }

        throw new Exception(sprintf(Exception::CANT_CREATE_MODEL,
            $this->modelClass,
            $this->name()
        ));
    }

    /**
     * Update a ProductCategory
     *
     * @example
     *     $productCategory = [Subbly\Model\ProductCategory instance];
     *     Subbly::api('subbly.product_category')->update($productCategory);
     *
     *     Subbly::api('subbly.product_category')->update($product_sku, array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @return \Subbly\Model\ProductCategory
     *
     * @api
     */
    public function update()
    {
        $args        = func_get_args();
        $productCategory = null;

        if (count($args) == 1 && $args[0] instanceof ProductCategory) {
            $productCategory = $args[0];
        }
        else if (count($args) == 2 && !empty($args[0]) && is_array($args[1]))
        {
            $productCategory = $this->find($args[0]);
            $productCategory->fill($args[1]);
        }

        if ($productCategory instanceof ProductCategory)
        {
            if ($this->fireEvent('updating', array($productCategory)) === false) return false;

            $productCategory->setCaller($this);
            $productCategory->save();

            $this->fireEvent('updated', array($productCategory));

            return $productCategory;
        }

        throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
            'Subbly\\Model\\ProductCategory',
            $this->name()
        ));
    }

    /**
     * Delete a ProductCategory
     *
     * @param \Subbly\Model\ProductCategory|string  $productCategory The productCategory_uid or the ProductCategory model
     *
     * @return \Subbly\Model\ProductCategory
     *
     * @pi
     */
    public function delete($productCategory)
    {
        if (!is_object($productCategory)) {
            $productCategory = $this->find($productCategory);
        }

        if ($productCategory instanceof ProductCategory)
        {
            if ($this->fireEvent('deleting', array($productCategory)) === false) return false;

            $productCategory->delete($this);

            $this->fireEvent('deleted', array($productCategory));
        }
    }

    /**
     * Service name
     */
    public function name()
    {
        return 'subbly.product_category';
    }
}
