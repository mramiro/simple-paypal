<?php namespace SimplePaypal\Cart;

use SimplePaypal\Support\Collection;

class Item extends Collection
{
  protected static $paypalVars = array(
    'amount',
    'discount_amount',
    'discount_amount2',
    'discount_rate',
    'discount_rate2',
    'discount_num',
    'item_name',
    'item_number',
    'quantity',
    'shipping',
    'shipping2',
    'tax',
    'tax_rate',
    'undefined_quantity',
    'weight',
    'weight_unit',
    'on0',
    'on1',
    'os0',
    'os1',
    'option_index',
    'option_select0',
    'option_amount0',
    'option_select1',
    'option_amount1'
  );

  public function __construct($id, $name, $price, array $vars = array())
  {
    $this->set('item_number', $id);
    $this->set('item_name', $name);
    $this->set('amount', $price);
    if (empty($vars['quantity'])) {
      $vars['quantity'] = 1;
    }
    foreach ($vars as $key => $value) {
      $this->set($key, $value);
    }
  }

  public function set($key, $value)
  {
    if (in_array($key, static::$paypalVars)) {
      return parent::set($key, $value);
    }
  }
}
