<?php

namespace Subbly\Api\Service;

use Subbly\Model\Order;

class OrderService extends Service
{
    /**
     * Return an empty model
     *
     * @return Subbly\Model\Order
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
