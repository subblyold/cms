<?php

namespace Subbly\CMS\Controllers\Api;

use Input;

use Subbly\Subbly;

class ProductsController extends BaseController
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
     * Get Product list
     *
     * @route GET /api/products
     * @authentication required
     */
    public function index()
    {
        list($offset, $limit) = $this->apiOffsetLimit();
        $options = $this->formatOptions(array(
            'offset'   => $offset,
            'limit'    => $limit,
            'includes' => $this->includes(),
        ));

        $products = Subbly::api('subbly.product')->all($options);

        return $this->jsonCollectionResponse('products', $products);
    }

    /**
     * Search one or many Product
     *
     * @route GET /api/products/search/?q=
     * @authentication required
     */
    public function search()
    {
        list($offset, $limit) = $this->apiOffsetLimit();
        $options = $this->formatOptions(array(
            'offset'   => $offset,
            'limit'    => $limit,
            'includes' => $this->includes(),
        ));

        $products = Subbly::api('subbly.product')->searchBy(Input::get('q'), $options);

        return $this->jsonCollectionResponse('products', $products, array(
            'query' => Input::get('q'),
        ));
    }

    /**
     * Get Product datas
     *
     * @route GET /api/products/{sku}
     * @authentication required
     */
    public function show($sku)
    {
        $options = $this->formatOptions(array(
            'includes' => $this->includes(),
        ));

        return $this->jsonResponse(array(
            'product' => Subbly::api('subbly.product')->find($sku, $options),
        ));
    }

    /**
     * Create a new Product
     *
     * @route POST /api/products/
     * @authentication required
     */
    public function store()
    {
        if (!Input::has('product')) {
            return $this->jsonErrorResponse('"product" is required.');
        }

        $product = Subbly::api('subbly.product')->create(Input::get('product'));

        return $this->jsonResponse(array(
            'product' => $product,
        ),
        array(
            'status' => array(
                'code'    => 201,
                'message' => 'Product created',
            ),
        ));
    }

    /**
     * Update a Product
     *
     * @route PUT|PATCH /api/products/{sku}
     * @authentication required
     */
    public function update($sku)
    {
        if (!Input::has('product')) {
            return $this->jsonErrorResponse('"product" is required.');
        }

        $product = Subbly::api('subbly.product')->update($sku, Input::get('product'));

        return $this->jsonResponse(array(
            'product' => $product,
        ),
        array(
            'status' => array('message' => 'Product updated'),
        ));
    }
}
