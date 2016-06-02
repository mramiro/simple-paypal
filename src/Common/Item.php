<?php namespace SimplePaypal\Common;

class Item extends VarCollection
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

  public function  __construct($name, $amount, $qty = 1, array $extra = array())
  {
    $this->item_name = $name;
    $this->amount = $amount;
    $this->quantity = $qty;
    parent::__construct($extra);
  }

}
