<?php

namespace Subbly\CMS\Controllers\Api;

use Illuminate\Support\Facades\Input;

use Subbly\Subbly;

class OrdersController extends BaseController
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
     * Get Order list
     *
     * @route GET /api/orders
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

        $orders = Subbly::api('subbly.order')->all($options);

        return $this->jsonCollectionResponse('orders', $orders);
    }

    /**
     * Search one or many Order
     *
     * @route GET /api/orders/search/?q=
     * @authentication required
     */
    public function search()
    {
        if (!Input::has('q')) {
            return $this->jsonErrorResponse('"q" is required.');
        }

        list($offset, $limit) = $this->apiOffsetLimit();
        $options = $this->formatOptions(array(
            'offset'   => $offset,
            'limit'    => $limit,
            'includes' => $this->includes(),
        ));

        $orders = Subbly::api('subbly.order')->searchBy(Input::get('q'), $options);

        return $this->jsonCollectionResponse('orders', $orders, array(
            'query' => Input::get('q'),
        ));
    }

    /**
     * Get Order datas
     *
     * @route GET /api/orders/{sku}
     * @authentication required
     */
    public function show($sku)
    {
        $options = $this->formatOptions(array(
            'includes' => $this->includes(),
        ));

        return $this->jsonResponse(array(
            'order' => Subbly::api('subbly.order')->find($sku, $options),
        ));
    }

    /**
     * Create a new Order
     *
     * @route POST /api/orders/
     * @authentication required
     */
    public function store()
    {
        if (!Input::has('order')) {
            return $this->jsonErrorResponse('"order" is required.');
        }

        $order = Subbly::api('subbly.order')->create(Input::get('order'));

        return $this->jsonResponse(array(
            'order' => $order,
        ),
        array(
            'status' => array(
                'code'    => 201,
                'message' => 'Order created',
            ),
        ));
    }

    /**
     * Update a Order
     *
     * @route PUT|PATCH /api/orders/{sku}
     * @authentication required
     */
    public function update($sku)
    {
        if (!Input::has('order')) {
            return $this->jsonErrorResponse('"order" is required.');
        }

        $order = Subbly::api('subbly.order')->update($sku, Input::get('order'));

        return $this->jsonResponse(array(
            'order' => $order,
        ),
        array(
            'status' => array('message' => 'Order updated'),
        ));
    }
}
