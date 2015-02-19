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

  /**
   * Stripe validation
   */
  protected $stripe_rules = array(
      'card_number'      => 'required'
    , 'card_expiryMonth' => 'required'
    , 'card_expiryYear'  => 'required'
    , 'card_cvv'         => 'required'
  );

  private function validate( $rules )
  {
    // Validate inputs
    $validator = Validator::make( Input::all(), $rules );

    if( $validator->fails() )
      return $validator;

    return false;
  }

  private function validationMsg( $msg )
  {
    $messages = new \Illuminate\Support\MessageBag;
    $messages->add('message', $msg );

    return $this->validationFails( $message );
  }

  private function validationFails( $validator )
  {
    return Redirect::back()
            ->withErrors( $validator, 'checkout' )
            ->withInput( Input::except('password') );
  }

  public function run()
  {
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

    // #4 Payment Gateway

    if( !Input::has('payment_gateway') )
    {
dd('missing payment gateway');
    }

    $payment_gateway = Input::get('payment_gateway');

      // get Payment settings
    $settings = Subbly::api('subbly.setting')->all()->toArray();

      // validate Stripe
    if( $payment_gateway == 'stripe' )
    {
      if( !$settings['subbly.payment.stripe.active'] )
        dd( 'Stripe not active');

      if( $validate = $this->validate( $this->stripe_rules ) )
        return $this->validationFails( $validate );
    }

      // validate Paypal Express
    if( $payment_gateway == 'paypal_express' )
    {
      if( !$settings['subbly.payment.paypal_express.active'] )
        dd( 'paypal_express not active');
    }

    // #5 Setup Order/Payment

      // Cart
    $cart    = Subbly::api('subbly.cart');
    $payment = Subbly::api('subbly.payment');
    $order   = Subbly::api('subbly.order');

    $cartContent = $cart->content()->toArray();
    $cartTotal   = $cart->total();
    $cardData    = null; // Stripe only

    // Setup payment gateway
    if( $payment_gateway == 'stripe' )
    {
      $cardData = [
          'number'      => Input::get('card_number')
        , 'expiryMonth' => Input::get('card_expiryMonth')
        , 'expiryYear'  => Input::get('card_expiryYear')
        , 'cvv'         => Input::get('card_cvv')
      ];

      $gateway = $payment->setProvider('Stripe');
      $gateway->setApiKey( $settings['subbly.payment.stripe.key'] );
    }
    else
    {
      $gateway = $payment->setProvider('PayPal_Express');
      $gateway->setUsername( $settings['subbly.payment.paypal_express.username'] );
      $gateway->setPassword( $settings['subbly.payment.paypal_express.password'] );
      $gateway->setSignature( $settings['subbly.payment.paypal_express.signature'] );
      $gateway->setTestMode( $settings['subbly.payment.paypal_express.testmode'] );
    }

    // After all test, 
    // if needed,
    // create a new user
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
        return $this->validationMsg( $e->getMessage() );
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

      // Payment
    try
    {
      $paymentResponse = $gateway->purchase([
          'amount'    => $cartTotal
        , 'currency'  => 'USD'
        , 'card'      => $cardData
        , 'returnUrl' => \URL::route('subbly.paymentcontroller.confirm')
        , 'cancelUrl' => \URL::route('subbly.paymentcontroller.cancel')
      ])->send();
    }
    catch( \Omnipay\Common\Exception\InvalidRequestException $e )
    {
dd( $e->getMessage());
    }

      // Transaction return
    $transactionRef = $paymentResponse->getTransactionReference();

      // Transaction fail
    if( is_null( $transactionRef ) )
    {
      dd( 'error paymant', $paymentResponse->getMessage() );
    }

    $orderData = array(
        'user_id'     => $user->id
      , 'total_price' => $cartTotal
      , 'gateway'     => $payment_gateway
    );

    $orderInst = $order->create( $orderData, $cartContent, $shipping, $billing );

    if( $orderInst )
    {
      // payment was successful: update database
      if( $paymentResponse->isSuccessful() )
      {
        $order->update( $orderInst->id, ['status' => 'confirm'] );
        $cart->destroy();
        
        return Redirect::route('subbly.paymentcontroller.confirm');
      }

      if( $paymentResponse->isRedirect() )
      {
        // Save transaction token
        Subbly::api('subbly.ordertoken')->create( array(
            'order_id' => $orderInst->id
          , 'token'    => $paymentResponse->getTransactionReference()
        ));

        // redirect to offsite payment gateway
        $paymentResponse->redirect();
      }
    }
    else
    {
      dd('save order fail');
    }
// dd( $order );
  }
}
