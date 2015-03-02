<?php

namespace Subbly\PostControllers;

use Subbly\Subbly;
use Input;
use Redirect;
use Validator;

class Cart
  extends Action 
{
  /**
   * Validations
   */
  protected $rules = array(
      'email'    => 'required|email'
    , 'password' => 'required'
  );

  private function getCartInst()
  {
    return Subbly::api('subbly.cart');
  }

  public function addTo()
  {
    $inputQty = Input::get('qty', 1);
    $inputId  = Input::get('id', false);
    $option   = Input::get('option', []);
    $redirect = Input::get('redirect', false);

    if( !is_numeric( $inputQty ) )
      $inputQty = 1;

    // TODO: add status restriction if 
    // current user is not loggued to Backend    
    $productOptions = [
        'includes' => [ 'options', 'translations' ]
      , 'where'    => [
            ['status', '!=', 'draft']
          , ['status', '!=', 'hidden']
        ]
    ];

    // Get product
    // -----------------

    try
    {
      $product = \Subbly\Subbly::api('subbly.product')->find( $inputId, $productOptions, 'id' )->toArray();
    }
    catch (\Exception $e)
    {
      throw new \InvalidArgumentException( $e->getMessage() );
    }

    // TODO: add $options check
    // TODO: add $product['sales'] check
    $cart = $this->getCartInst()
            ->add( 
                  $product
                , $inputQty
                , $option 
              );

    if( Input::has('redirect') )
      return Redirect::to( Input::get('redirect') );

    return Redirect::back();
  }

  public function updateRow()
  {
    $rowId    = Input::get('cartRowId', false);
    $inputQty = Input::get('qty', 1);

    if( !is_numeric( $inputQty ) )
      throw new \InvalidArgumentException( 'Quantity must me an integer' );

    try
    {
      $cart = $this->getCartInst();

      $row  = $cart->get( $rowId );

      if( is_null( $row ) )
        throw new \Exception('Unkown cart row');
    }
    catch (\Exception $e)
    {
      throw new \InvalidArgumentException( $e->getMessage() );
    }

    if( $inputQty > 0 )
    {
      $cart->update( $rowId, $inputQty );
    }
    else
    {
      $cart->remove( $rowId );
    }

    return Redirect::back();
  }

  public function removeRow()
  {
    $rowId    = Input::get('cartRowId', false);

    try
    {
      $cart = $this->getCartInst();

      $row  = $cart->get( $rowId );

      if( is_null( $row ) )
        throw new \Exception('Unkown cart row');
    }
    catch (\Exception $e)
    {
      throw new \InvalidArgumentException( $e->getMessage() );
    }

    $cart->remove( $rowId );

    return Redirect::back();
  }

  public function emptyCart()
  {
    $this->getCartInst()->destroy();

    if( Input::has('redirect') )
      return Redirect::to( Input::get('redirect') );

    return Redirect::back();
  }
}
