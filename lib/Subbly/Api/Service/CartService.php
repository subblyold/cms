<?php

namespace Subbly\Api\Service;

use Subbly\Model\Order;

class CartService extends Service
{
    /**
     * Access to the cart
     */
    public function access()
    {
        $order = new Order;
        // Session::get('cart.order');

        return $order;
    }

    /**
     * Service name
     */
    public function name()
    {
        return 'subbly.cart';
    }
}
