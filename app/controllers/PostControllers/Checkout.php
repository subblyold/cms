<?php

namespace Subbly\PostControllers;

use Subbly\Subbly;
use Input;
use Redirect;
use Validator;

class Checkout
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

  /**
   * New Customer Validation
   */
  protected $customer_rules = array(
      'customer_email'                 => 'required|email'
    , 'customer_password'              => 'required|confirmed'
    , 'customer_password_confirmation' => 'required'
    , 'shipping_firstname'             => 'required'
    , 'shipping_lastname'              => 'required'
  );

  /**
   * Shipping address validation
   */
  protected $shipping_rules = array(
      'shipping_firstname' => 'required'
    , 'shipping_lastname'  => 'required'
    , 'shipping_address1'  => 'required'
    , 'shipping_city'      => 'required'
    , 'shipping_zipcode'   => 'required'
    , 'shipping_country'   => 'required'
  );

  /**
   * Billing address validation
   */
  protected $billing_rules = array(
      'billing_firstname' => 'required'
    , 'billing_lastname'  => 'required'
    , 'billing_address1'  => 'required'
    , 'billing_city'      => 'required'
    , 'billing_zipcode'   => 'required'
    , 'billing_country'   => 'required'
  );

  private function validate( $rules )
  {
    // Validate inputs
    $validator = Validator::make( Input::all(), $rules );

    if( $validator->fails() )
      return $validator;

    return false;
  }

  private function validationFails( $validator )
  {
    return Redirect::back()
            ->withErrors( $validator, 'checkout' )
            ->withInput( Input::except('password') );
  }

  public function run()
  {
    $messages = new \Illuminate\Support\MessageBag;
    $newUser  = false;
    $config   = \Config::get('subbly.checkout');

    // #1: user logged or not
    $user = ( Subbly::api('subbly.user')->check() )
            ? Subbly::api('subbly.user')->currentUser()
            : false;

    // #1.1: new customer
    if( !$user )
    {
      // Validate inputs
      if( $validate = $this->validate( $this->customer_rules ) )
        return $this->validationFails( $validate );

      $newUser = true;
    }

    // #2: shipping address
      // Validate inputs
    if( $validate = $this->validate( $this->shipping_rules ) )
      return $this->validationFails( $validate );

      // Map Inputs
    $shipping = array(
        'firstname'          => Input::get('shipping_firstname')
      , 'lastname'           => Input::get('shipping_lastname')
      , 'address1'           => Input::get('shipping_address1')
      , 'address2'           => Input::get('shipping_address2', '')
      , 'city'               => Input::get('shipping_city')
      , 'zipcode'            => Input::get('shipping_zipcode')
      , 'country'            => Input::get('shipping_country')
      , 'phone_work'         => Input::get('shipping_phone_work', '')
      , 'phone_home'         => Input::get('shipping_phone_home', '')
      , 'phone_mobile'       => Input::get('shipping_phone_mobile', '')
      , 'other_informations' => Input::get('shipping_other_informations', '')
    );

    // #3: billing address
      // 
    $billing = Input::get('different_billing', false);

    if( $billing )
    {
      // Validate inputs
      if( $validate = $this->validate( $this->billing_rules ) )
        return $this->validationFails( $validate );

        // Map Inputs
      $billing = array(
          'firstname'          => Input::get('billing_firstname')
        , 'lastname'           => Input::get('billing_lastname')
        , 'address1'           => Input::get('billing_address1')
        , 'address2'           => Input::get('billing_address2', '')
        , 'city'               => Input::get('billing_city')
        , 'zipcode'            => Input::get('billing_zipcode')
        , 'country'            => Input::get('billing_country')
        , 'phone_work'         => Input::get('billing_phone_work', '')
        , 'phone_home'         => Input::get('billing_phone_home', '')
        , 'phone_mobile'       => Input::get('billing_phone_mobile', '')
        , 'other_informations' => Input::get('billing_other_informations', '')
      );

    }

    // After all test 
    if( $newUser )
    {
      try
      {
        $userApi     = Subbly::api('subbly.user');
        $credentials = array(
              'firstname' => Input::get('shipping_firstname')
            , 'lastname'  => Input::get('shipping_lastname')
            , 'email'     => Input::get('customer_email')
            , 'password'  => Input::get('customer_password')
            , 'activated' => 1
         );

        // if( $config['account']['autolog'] )
        //   $credentials['activated'] = true;

        $user    = $userApi->create( $credentials );
      }
      catch( \Subbly\Api\Service\Exception $e )
      {
        $messages->add('message', $e->getMessage() );

        return Redirect::back()
                ->withErrors( $messages, 'checkout' )
                ->withInput( Input::except('password') );
      }

      // TODO: allow aulogin on create account
      // if( $config['account']['autolog'] )
      // {
      //   try
      //   {
      //     // Log the user in
      //     $userApi->login( $user );
      //   }
      //   catch( \Cartalyst\Sentry\Users\UserNotActivatedException $e )
      //   {
      //     $messages->add('message', $e->getMessage() );

      //     return Redirect::back()
      //             ->withErrors( $messages, 'checkout' )
      //             ->withInput( Input::except('password') );
      //   }        
      // }
    }

    $cardData = [
        'number'      => '4242424242424242'
      , 'expiryMonth' => '6'
      , 'expiryYear'  => '2016'
      , 'cvv'         => '123'
    ];

    $payment = Subbly::api('subbly.payment');
    $cart    = Subbly::api('subbly.cart')->content()->toArray();
    $order   = array(
        'user_id'     => $user->id
      , 'total_price' => Subbly::api('subbly.cart')->total()
    );

    $ret = Subbly::api('subbly.order')->create( $order, $cart, $shipping, $billing );

    if( $ret )
    {
      $gateway = $payment->setProvider('Stripe');
      $gateway->setApiKey('sk_test_T4ENZyAcLQ8oDlgSl1Cq6HVf');

      $response = $gateway->purchase([
          'amount'   => Subbly::api('subbly.cart')->total()
        , 'currency' => 'USD'
        , 'card'     => $cardData
      ])->send();

      if( $response->isSuccessful() )
      {
        // payment was successful: update database
        dd($response);
      }
      elseif( $response->isRedirect() )
      {
        // redirect to offsite payment gateway
        dd($response);
        $response->redirect();
      }
      else 
      {
        // payment failed: display message to customer
        echo $response->getMessage();
      }
    }
dd( $ret );
  }
}
