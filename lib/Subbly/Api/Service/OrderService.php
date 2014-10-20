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
     *
     */
    public function name()
    {
        return 'subbly.order';
    }
}
