<?php

namespace Subbly\PostControllers;

use Subbly\Subbly;
use Input;
use Redirect;
use Validator;

class Address
  extends Action 
{
  /**
   * Validations
   */
  protected $rules = array(
      'name'      => 'required'
    , 'firstname' => 'required'
    , 'lastname'  => 'required'
    , 'address1'  => 'required'
    , 'zipcode'   => 'required'
    , 'city'      => 'required'
    , 'country'   => 'required'
  );

  public function run()
  {
    $validator = Validator::make( Input::all(), $this->rules );
    $messages  = new \Illuminate\Support\MessageBag;

    if( $validator->fails() ) 
    {
      return Redirect::back()
              ->withErrors( $validator, 'address' )
              ->withInput( Input::except('password') );
    }

    $user = Subbly::api('subbly.user')->currentUser();
    $id   = Input::get('id', false);

    $method = ( $id ) ? 'update' : 'create';

    $userAddress = Subbly::api('subbly.user_address')->{$method}(Input::all(), $user);

    return Redirect::back();
  }
}
