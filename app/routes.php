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


Route::get('/test-option', 'TestOptionController@index');
Route::post('/test-option', 'TestOptionController@save');

/*
 * You can set you own route bellow
 * see Laravel's doc form more information:
 * http://laravel.com/docs/4.2/routing
 */

// Route::get('/your-route', 'YourRoute@yourMethod');



/**
 * Backend routes
 */
Route::group(array(
    'prefix'    => Config::get( 'subbly.apiUri', 'api' ),
    'namespace' => 'Api',
), function() {

    // AuthController
    Route::get('/auth/test-credentials', 'AuthController@testCredentials');

    // WelcomeController
    Route::get('/welcome', 'WelcomeController@index');

    // UsersController
    Route::get('/users/search', 'UsersController@search');
    Route::resource('/users', 'UsersController', array('except' => array('create', 'edit')));

    // ProductsController
    Route::resource('/products', 'ProductsController', array('except' => array('create', 'edit')));
});

/*
 * Register template driven Frontend
 * Comment this part if you want to user 
 * your own controller
 */

Route::any('{url}', 'AutoController@run')->where('url', '.*');

/*
 * You can set you own route bellow
 * see Laravel's doc form more information:
 * http://laravel.com/docs/4.2/routing
 */

// Route::get('/your-route', 'YourRoute@yourMethod');
