<?php

/*
 * Register Backend routes before public routes.
 */
Route::get( Config::get( 'subbly.backendUri', 'backend' ), function()
{
    return View::make('backend')->with( 'environment', App::environment() );
});

/**
 * Backend proxy API routes
 */
Route::group(array(
    'prefix'    => Config::get('subbly.apiUri', 'api') . '/v1',
    'namespace' => 'Subbly\\CMS\\Controllers\\Api',
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

    // SettingsController
    Route::get('/settings', 'SettingsController@index');
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
