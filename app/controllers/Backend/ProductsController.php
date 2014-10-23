<?php

namespace Backend;

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
     * @route GET /backend/products
     * @authentication required
     */
    public function index()
    {
        return $this->jsonResponse(array(
            'products' => Subbly::api('subbly.product')->all(),
        ));
    }

    /**
     * Get Product datas
     *
     * @route GET /backend/products/:sku
     * @authentication required
     */
    public function show($sku)
    {
        return $this->jsonResponse(array(
            'product' => Subbly::api('subbly.product')->find($sku, array(
                'with_images'     => true,
                'with_options'    => true,
                'with_categories' => true,
            )),
        ));
    }

    /**
     * Create a new Product
     *
     * @route POST /backend/products/
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
     * @route POST /backend/products/
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
