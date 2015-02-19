<?php

namespace Subbly\PostControllers;

use Subbly\Subbly;
use Input;
use Redirect;
use Validator;

class Session
  extends Action 
{
  /**
   * Validations
   */
  protected $rules = array(
      'email'    => 'required|email'
    , 'password' => 'required'
  );

  public function login()
  {
    $validator = Validator::make( Input::all(), $this->rules );
    $messages  = new \Illuminate\Support\MessageBag;

    if( $validator->fails() ) 
    {
      return Redirect::back()
              ->withErrors( $validator, 'login' )
              ->withInput( Input::except('password') );
    }

    try 
    {
      $credentials   = array(
          'login'    => Input::get('email')
        , 'password' => Input::get('password')
      );

      $authenticated = Subbly::api('subbly.user')
                          ->authenticate( $credentials );
    }
    catch( \Exception $e )
    {
      if( in_array( get_class( $e ), array(
          'Cartalyst\\Sentry\\Users\\UserNotActivatedException',
          'Cartalyst\\Sentry\\Users\\UserSuspendedException',
          'Cartalyst\\Sentry\\Users\\UserBannedException',
      ))) 
      {
        $messages->add('message', $e->getMessage() );

        return Redirect::back()
                ->withErrors( $messages, 'login' )
                ->withInput( Input::except('password') );
      }
      else if( in_array( get_class( $e ), array(
          'Cartalyst\\Sentry\\Users\\LoginRequiredException',
          'Cartalyst\\Sentry\\Users\\PasswordRequiredException',
          'Cartalyst\\Sentry\\Users\\WrongPasswordException',
          'Cartalyst\\Sentry\\Users\\UserNotFoundException',
      ))) 
      {
        $messages->add('message', $e->getMessage() );

        return Redirect::back()
                ->withErrors( $messages, 'login' )
                ->withInput( Input::except('password') );
      }
      dd('fatal');
      return $this->errorResponse('FATAL ERROR!', 500);
    }

    if( Input::has('redirect') )
      return Redirect::to( Input::get('redirect') );

    return Redirect::back();
  }

  public function logout()
  {
    Subbly::api('subbly.user')->logout();

    if( Input::has('redirect') )
      return Redirect::to( Input::get('redirect') );

    return Redirect::back();
  }
}
