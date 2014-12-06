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
