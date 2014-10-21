<?php

namespace Backend;

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
     * @route GET /backend/products/:uid
     * @authentication required
     */
    public function show($uid)
    {
        return $this->jsonResponse(array(
            'product' => Subbly::api('subbly.product')->find($uid, array(
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
        $product = Subbly::api('subbly.product')->create(Input::get('product'));

        return $this->jsonResponse(array(
            'product' => $product,
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
        $product = Subbly::api('subbly.product')->update(Input::get('product_id'), Input::get('product'));

        return $this->jsonResponse(array(
            'product' => $product,
        ));
    }
}
