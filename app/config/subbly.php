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
    | Theme
    |--------------------------------------------------------------------------
    |
    | Selected theme
    |
    */

  , 'theme' => 'pocket'

    /*
    |--------------------------------------------------------------------------
    | Front-end URI list
    |--------------------------------------------------------------------------
    |
    | URI patterns
    |
    */

  , 'frontendUri' => array(
        '/'                                       => 'Home'
      , '/products'                               => 'Catalog'
      , '/products/:id/:slug'                     => 'product'
      , '/products/?page/:category'               => 'CatalogCategory'
      , '/products/?page/:category/:subcategory'  => 'CatalogSubCategory'
      // , '/add-to-cart'                            => 'AddToCart'
      // , '/best-seller-2014/:cat_1'                => 'best-seller'
      // , '/best-seller-2014'                       => 'best-seller'
      // , '/account'                                => 'Account'
      // , '/account/auth'                           => 'AccountAuth'
      // , '/account/create'                         => 'AccountCreate'
      // , '/account/orders'                         => 'AccountOrders'
      // , '/account/addresses'                      => 'AccountAddresses'
      // , '/cart'                                   => 'Cart'
      // , '/cart/sigin'                             => 'CartSignin'
      // , '/cart/address'                           => 'CartAddress'
      // , '/cart/shipping'                          => 'CartShipping'
      // , '/cart/checkout'                          => 'CartCheckout'
      // , '/cart/payment'                           => 'CartPayment'
      // , '/cart/confirm'                           => 'CartConfirm'
      // , '/cart/cancel'                            => 'CartCancel'
      , '/about-marmouze'                         => 'marmouze'
    )

);
