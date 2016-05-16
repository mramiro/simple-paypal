<?php namespace SimplePaypal\Html\Carts;

final class AddToHostedCart extends Base
{
  protected static $allowed = array(
    'add'
  );

  public function __construct(array $items = array())
  {
    parent::__construct($items);
    $this->add = true;
  }
}
