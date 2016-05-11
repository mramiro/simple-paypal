<?php namespace SimplePaypal\Buttons;

abstract class Cart extends Button
{
  protected static $allowed = array(
    'address_override',
    'currency_code',
    'custom',
    'handling',
    'invoice',
    'tax_cart',
    'weight_cart',
    'weight_unit'
  );
}
