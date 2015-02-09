<?php

return array(
    
    /*
    |--------------------------------------------------------------------------
    | API URI prefix
    |--------------------------------------------------------------------------
    |
    | Specifies the URI prefix used for accessing REST service.
    |
    */

    'apiUri' => 'api'

    /*
    |--------------------------------------------------------------------------
    | Back-end URI prefix
    |--------------------------------------------------------------------------
    |
    | Specifies the URI prefix used for accessing back-end pages.
    |
    */

  , 'backendUri' => 'backend'

    /*
    |--------------------------------------------------------------------------
    | Frontage URI prefix
    |--------------------------------------------------------------------------
    |
    | Specifies the URI prefix used for accessing frontage's action pages.
    |
    */

  , 'frontagePostUri' => 'subbly-post'

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

  , 'frontendLocales' => array('en', 'fr', 'mx')

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

  , 'frontendFallbackLocale' => 'en'

    /*
    |--------------------------------------------------------------------------
    | Front-end URI list
    |--------------------------------------------------------------------------
    |
    | URI patterns
    |
    */

  , 'frontageUri' => array(
        '/'                                            => 'index'
      , '/products/{productSku}-{productSlug}'         => 'product'
      , '/products/{page?}/{category?}/{subcategory?}' => 'catalog'
      , '/test'                                        => 'test'
      , '/signin'                                      => 'signin'
      , '/account/address/{addressId?}'                => 'account.address@signin'
      , '/account'                                     => 'account@signin'
      , '/cart'                                        => 'cart'
      , '/checkout'                                    => 'checkout'
    )
  
    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | Selected theme
    |
    */

  , 'theme' => 'pocket'
);
