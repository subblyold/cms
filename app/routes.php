<?php

/*
 * Register Backend routes before public routes.
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

/*
 * Register template driven Frontend
 * Comment this part if you want to user 
 * your own controller
 */

// Route::any('.*', 'AutoController@run');

Route::any('{url}', 'AutoController@run')->where('url', '.*');

/*
 * You can set you own route bellow
 * see Laravel's doc form more information:
 * http://laravel.com/docs/4.2/routing
 */

// Route::get('/your-route', 'YourRoute@yourMethod');


