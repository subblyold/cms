<?php

use Subbly\Subbly;

class TestController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		// Subbly\Subbly::init();
		// echo Config::get('subbly.backendUri', 'backend');
		$credentials   = array(
		    'login'    => 'michael@scenedata.com',
		    'password' => 'michael',
		);
		$authenticated = Subbly::api('subbly.user')->authenticate($credentials);
var_dump($authenticated);
		// return View::make('hello');
	}

}
