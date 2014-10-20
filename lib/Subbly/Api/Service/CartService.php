<?php

namespace Subbly\Api\Service;

use Subbly\Model;

class CartService extends Service
{
    /**
     *
     */
    public function access()
    {
        Session::get('cart.order');

        return new Model\Order;
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.cart';
    }
}
