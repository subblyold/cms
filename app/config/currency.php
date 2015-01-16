<?php

return array(

  /*
   |--------------------------------------------------------------------------
   | Default currency
   |--------------------------------------------------------------------------
   */

  'default' => 'USD',

  /*
   |--------------------------------------------------------------------------
   | Add a single space between value and currency symbol
   |--------------------------------------------------------------------------
   */

  'use_space' => false,

  /*
   |--------------------------------------------------------------------------
   | Add a single space between value and currency symbol
   |--------------------------------------------------------------------------
   */

  'table' => [
      'USD' => [
          'symbol_left'    => '$'
        , 'symbol_right'   => ''
        , 'decimal_place'  => 2
        , 'decimal_point'  => '.'
        , 'thousand_point' => ','
      ]
    , 'EUR' => [
          'symbol_left'    => '€'
        , 'symbol_right'   => ''
        , 'decimal_place'  => 2
        , 'decimal_point'  => '.'
        , 'thousand_point' => ','
      ]
  ]

);
