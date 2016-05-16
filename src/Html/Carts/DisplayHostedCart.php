<?php namespace SimplePaypal\Html\Carts;

final class AddToHostedCart extends Base
{
  protected static $allowed = array(
    'display',
    'shopping_url'
  );

  public function __construct(array $items = array())
  {
    parent::__construct($items);
    $this->display = true;
  }
}
