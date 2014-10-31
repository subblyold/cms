<?php

namespace Subbly\Api\Service;

use Subbly\Model\Order;

class OrderService extends Service
{
    protected $modelClass = 'Subbly\\Model\\Order';

    protected $includableRelationships = array('shipping_address', 'billing_address', 'products');

    /**
     * Return an empty model
     *
     * @return \Subbly\Model\Order
     *
     * @api
     */
    public function newOrder()
    {
        return new Order();
    }

    /**
     * Service name
     */
    public function name()
    {
        return 'subbly.order';
    }
}
