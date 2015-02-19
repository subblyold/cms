<?php

namespace Subbly\PaymentControllers;

use Subbly\Subbly;
use Input;

class Payment
  extends \Controller
{
  public function confirm()
  {
    $order = Subbly::api('subbly.ordertoken')->find(Input::get('token'));
    dd($order->order);
  }

  public function cancel()
  {
    dd('cancel');
    $order = Subbly::api('subbly.ordertoken')->find(Input::get('token'));
    dd($order->order);
  }
}
