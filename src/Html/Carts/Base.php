<?php namespace SimplePaypal\Html\Carts;

use SimplePaypal\Html\Button;

abstract class Base extends Button
{
  protected static $allowed = array(
    // Payment transactions (applicable to all carts)
    'address_override',
    'currency_code',
    'custom',
    'handling',
    'invoice',
    'tax_cart',
    'weight_cart',
    'weight_unit',
    // Specific to carts
    'business',
    'discount_amount_cart',
    'discount_rate_cart',
    'handling_cart',
    'paymentaction',
  );

  public function __construct(array $items = array())
  {
    $this->cmd = '_cart';
    parent::__construct($items);
  }
}
