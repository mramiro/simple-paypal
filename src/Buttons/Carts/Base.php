<?php namespace SimplePaypal\Buttons\Carts;

use SimplePaypal\Buttons\Button;

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
  protected $buttonType = 'ShoppingCart';

  public function __construct(array $items = array())
  {
    $this->cmd = '_cart';
    parent::__construct($items);
  }

  public function setDiscount($discount, $relative = false)
  {
    if ($relative) {
      $this->discount_rate_cart = $discount;
    }
    else {
      $this->discounte_amount_cart = $discount;
    }
    return $this;
  }
}
