<?php

namespace Subbly\CMS\Controllers\Api;

use Illuminate\Support\Facades\Input;

use Subbly\Subbly;

class ProductCategoriesController extends BaseController
{
    /**
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('@processAuthentication');
    }


    /**
     * Get list of ProductCategory for a Product
     *
     * @route GET /api/v1/products/:product_sku/categories
     * @authentication required
     */
    public function index($product_sku)
    {
        $product = Subbly::api('subbly.product')->find($product_sku);

        list($offset, $limit) = $this->apiOffsetLimit();
        $options = $this->formatOptions(array(
            'offset' => $offset,
            'limit'  => $limit,
        ));

        $productCategories = Subbly::api('subbly.product_category')->findByProduct($product, $options);

        return $this->jsonCollectionResponse('product_categories', $productCategories);
    }

    /**
     * Create a new ProductCategory
     *
     * @route POST /api/v1/products/:product_sku/categories
     * @authentication required
     */
    public function store($product_sku)
    {
        $product = Subbly::api('subbly.product')->find($product_sku);

        if (!Input::has('product_category')) {
            return $this->jsonErrorResponse('"product_category" is required.');
        }

        $productCategory = Subbly::api('subbly.product_category')->create(Input::get('product_category'), $product);

        return $this->jsonResponse(array(
            'product_category' => $productCategory,
        ),
        array(
            'status' => array(
                'code'    => 201,
                'message' => 'ProductCategory created',
            ),
        ));
    }

    /**
     * Update a ProductCategory
     *
     * @route PUT|PATCH /api/v1/products/:product_sku/categories/:uid
     * @authentication required
     */
    public function update($product_sku, $uid)
    {
        $product = Subbly::api('subbly.product')->find($product_sku);

        if (!Input::has('product_category')) {
            return $this->jsonErrorResponse('"product_category" is required.');
        }

        $productCategory = Subbly::api('subbly.product_category')->update($uid, Input::get('product_category'));

        return $this->jsonResponse(array(
            'product_category' => $productCategory,
        ),
        array(
            'status' => array(
                'code'    => 200,
                'message' => 'ProductCategory updated',
            ),
        ));
    }

    /**
      * Delete a ProductCategory
      *
      * @route DELETE /api/v1/products/:product_sku/categories/:uid
      * @authentication required
     */
    public function delete($product_sku, $uid)
    {
        $product = Subbly::api('subbly.product')->find($product_sku);

        $productCategory = Subbly::api('subbly.product_category')->delete($uid);

        return $this->jsonResponse(array(
            'product_category' => $productCategory,
        ),
        array(
            'status' => array(
                'code'    => 200,
                'message' => 'ProductCategory deleted',
            ),
        ));
    }
}
