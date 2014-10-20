<?php

namespace Subbly\Api\Service;

use Subbly\Model\Product;

class ProductService extends Service
{
    /**
     * Return an empty model
     *
     * @return Subbly\Model\Product
     *
     * @api
     */
    public function newProduct()
    {
        return new Product();
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.product';
    }
}
