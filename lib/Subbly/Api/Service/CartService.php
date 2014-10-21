<?php

namespace Subbly\Api\Service;

use Subbly\Model\Order;

class CartService extends Service
{
    /**
     *
     */
    public function access()
    {
        Session::get('cart.order');

        return new Order;
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.cart';
    }
}
