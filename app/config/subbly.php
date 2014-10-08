<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Back-end URI prefix
    |--------------------------------------------------------------------------
    |
    | Specifies the URI prefix used for accessing back-end pages.
    |
    */

    'backendUri' => 'backend'

    /*
    |--------------------------------------------------------------------------
    | Front-end URI list
    |--------------------------------------------------------------------------
    |
    | URI patterns
    |
    */

  , 'frontendUri' => array(
        '/'                       => 'Home'
      , '/account'                => 'Account'
      , '/account/auth'           => 'AccountAuth'
      , '/products'               => 'Catalog'
      , '/products/:cat_1'        => 'CatalogCategory'
      , '/products/:cat_1/:cat_2' => 'CatalogSubCategory'
      , '/add-to-cart'            => 'AddToCart'
      , '/cart'                   => 'Cart'
      , '/cart/sigin'             => 'CartSignin'
      , '/cart/address'           => 'CartAddress'
      , '/cart/shipping'          => 'CartShipping'
      , '/cart/checkout'          => 'CartCheckout'
      , '/cart/payment'           => 'CartPayment'
      , '/cart/confirm'           => 'CartConfirm'
      , '/cart/cancel'            => 'CartCancel'
    )
);
