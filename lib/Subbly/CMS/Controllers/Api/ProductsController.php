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
     * Get Product datas
     *
     * @route GET /api/products/{{sku}}
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
     * @route POST /api/products/
     * @authentication required
     */
    public function update()
    {
        if (!Input::has('product_sku')) {
            return $this->jsonErrorResponse('"product_sku" is required.');
        }
        if (!Input::has('product')) {
            return $this->jsonErrorResponse('"product" is required.');
        }

        $product = Subbly::api('subbly.product')->update(Input::get('product_sku'), Input::get('product'));

        return $this->jsonResponse(array(
            'product' => $product,
        ),
        array(
            'status' => array('message' => 'Product updated'),
        ));
    }
}
