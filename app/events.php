<?php

use Subbly\Subbly;


/*
|--------------------------------------------------------------------------
| Global Past Events Hooks
|--------------------------------------------------------------------------
|
| Below you will find all the "after" events for the application
| which may be used to do any work after a request. 
|
*/
  
  // Users

Subbly::events()->listen('subbly.user:creating', function( $user ) 
{
  // delete all users stats
});

Subbly::events()->listen('subbly.user:created', function( $user ) 
{
  // register all users stats
});

  // Orders

Subbly::events()->listen('subbly.orders:created', function( $order ) 
{
  // delete all orders stats
});

// Attach file to model on upload
Subbly::events()->listen('subbly.upload:created', function( $file ) 
{
  if( $file['file_type'] == 'product_image' )
  {
    if( isset( $file['sku'] ) && $file['sku'] !== 'false' )
    {
      $product = Subbly::api('subbly.product')->find( $file['sku'] );

      $productImage = Subbly::api('subbly.product_image')->create(array(
          'filename'   => $file['filename'] 
        , 'product_id' => $file['sku']
      ), $product);
    }
  }
});

// Attach file to model on upload
Subbly::events()->listen('subbly.product:created', function( $product ) 
{
  if (Input::has('product_image')) {
      $files = Input::get('product_image');

      foreach ($files as $file)
      {
        $productImage = Subbly::api('subbly.product_image')->create(array(
          'filename'   => $file['filename'] 
        , 'product_id' => $product->sku
        ), $product);
      }
  }
});
