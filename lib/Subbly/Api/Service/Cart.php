<?php

namespace Subbly\Api\Service;

use Subbly\Api\Service;
use Subbly\Model;

class Cart extends Service
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
