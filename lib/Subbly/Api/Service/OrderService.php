<?php

namespace Subbly\Api\Service;

use Subbly\Model;

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
        return new Model\Order();
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.order';
    }
}
