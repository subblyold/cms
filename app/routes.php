<?php

/*
 * Register Backend routes before public routes.
 */

Route::group(array(
    'prefix' => Config::get( 'subbly.backendUri', '/backend' )
), function() {

    $displayBackend = function()
    {
        return View::make('backend')->with( 'environment', App::environment() );
    };

    Route::get( '/' ,     $displayBackend );
    Route::get( '{url}' , $displayBackend )->where('url', '.*');
});

// Route::any('/test', 'TestController@showWelcome');

/*
 * Return empty response.
 * Allow natural autocomplete on ajax form
 */

Route::any('/void', function()
{
    $response = Response::make( array(), 204 );

    $response->header('Content-Type', 'json');

    return $response;
});

Route::get('/static/templates', 'TplController@setup');

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
