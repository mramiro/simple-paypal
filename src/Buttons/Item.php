<?php namespace SimplePaypal\Buttons;

use SimplePaypal\Support\ConstrainedCollection;

class Item extends ConstrainedCollection
{
  protected static $allowed = array(
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

  public function  __construct($id, $name, $amount, array $extra = array())
  {
    $this->setId($id);
    $this->setName($name);
    $this->setAmount($amount);
    $this->setQuantity(1);
    parent::__construct($extra);
  }

  protected function canBeSet($key)
  {
    return in_array($key, static::$allowed);
  }

  public function setId($id)
  {
    return $this->set('item_number', $id);
  }

  public function setName($name)
  {
    return $this->set('item_name', $name);
  }

  public function setAmount($amount)
  {
    return $this->set('amount', $amount);
  }

  public function setQuantity($qty)
  {
    return $this->set('quantity', $qty);
  }
}
