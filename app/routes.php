<?php

/*
* Register Backend routes before all user routes.
*/
App::before(function($request) 
{
  /*
   * Other pages
   */
  Route::group( [ 'prefix' => Config::get( 'subbly.backendUri', 'backend' ) ], function() 
  {
    Route::any( '{slug}', 'TestController@showWelcome' );
    // echo 'ok';
    // Route::any( '{slug}', 'Backend\Classes\BackendController@run' )->where( 'slug', '(.*)?' );
  });

  /*
   * Entry point
   */
  // Route::any( Config::get( 'subbly.backendUri', 'backend' ), 'Backend\Classes\BackendController@run' );
});

// Route::get('/', 'HomeController@showWelcome');

// Route::get('/auth', 'AuthController@askPermission');


