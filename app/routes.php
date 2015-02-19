<?php

/*
 * E-Commerce `POST` contollers.
 * do not touch anything unless you
 * know what you're doing
 */
Route::group( array(
    'namespace' => 'Subbly\PostControllers'
  , 'prefix'    => Config::get( 'subbly.actionControllerUri', '/action' )
  , 'before'    => 'csrf'
), function() 
{
  /*
   * Login/logout
   */
  Route::post('/login', [
      'as'     => 'subbly.postcontroller.login'
    , 'uses'   => 'Session@login'
  ]);

  Route::post('/logout', [
      'as'     => 'subbly.postcontroller.logout'
    , 'uses'   => 'Session@logout'
  ]);

  /*
   * User
   */

  /*
   * Cart
   */
  Route::post('/cart/add', [
      'as'     => 'subbly.postcontroller.addtocart'
    , 'uses'   => 'Cart@addTo'
  ]);

  Route::post('/cart/update', [
      'as'     => 'subbly.postcontroller.updatecart'
    , 'uses'   => 'Cart@updateRow'
  ]);

  Route::post('/cart/remove', [
      'as'     => 'subbly.postcontroller.removecart'
    , 'uses'   => 'Cart@removeRow'
  ]);

  Route::post('/cart/empty', [
      'as'     => 'subbly.postcontroller.emptycart'
    , 'uses'   => 'Cart@emptyCart'
  ]);

  Route::post('/account/address/{id?}', [
      'as'     => 'subbly.postcontroller.address'
    , 'uses'   => 'Address@run'
  ]);

  /*
   * Checkout
   */
  Route::post('/checkout', [
      'as'     => 'subbly.postcontroller.checkout'
    , 'uses'   => 'Checkout@run'
  ]);

});


/*
 * Payment response controller.
 * Do not touch anything unless you
 * know what you're doing
 */
Route::group( array(
    'namespace' => 'Subbly\PaymentControllers'
  , 'prefix'    => Config::get( 'subbly.paymenttUri', '/payment' )
), function() 
{
  // Route::get('checkout', array('as' => 'subbly.postcontroller.checkout', 'uses' => 'Checkout@index'));
  /*
   * Confirm
   */
  Route::get('/confirm', [
      'as'     => 'subbly.paymentcontroller.confirm'
    , 'uses'   => 'Payment@confirm'
  ]);

  /*
   * Cancel
   */
  Route::get('/cancel', [
      'as'     => 'subbly.paymentcontroller.cancel'
    , 'uses'   => 'Payment@cancel'
  ]);
});

/*
 * You can set you own route bellow
 * see Laravel's doc form more information:
 * http://laravel.com/docs/4.2/routing
 */

// Route::get('/your-route', 'YourRoute@yourMethod');
