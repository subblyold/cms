<?php

namespace Subbly\PaymentControllers;

use Subbly\Subbly;
use Input;

class Payment
  extends \Controller
{
  public function confirm()
  {
    if( Input::has('token') )
    {
      $orderToken = Subbly::api('subbly.ordertoken')
                    ->find( Input::get('token') );

      $orderInst  = Subbly::api('subbly.order')
                    ->update( $orderToken->order_id, ['status' => 'confirmed'] );

      Subbly::api('subbly.cart')->destroy();
    }

    echo 'ok';
  }

  public function cancel()
  {
    dd('cancel');
    $order = Subbly::api('subbly.ordertoken')->find(Input::get('token'));
    dd($order->order);
  }
}
