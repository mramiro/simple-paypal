<?php namespace SimplePaypal\Buttons\Carts;

use SimplePaypal\Buttons\Item;

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

  public function addItem(Item $item)
  {
    $this->item = $item;
  }

  public function getVars()
  {
    $vars = parent::getVars();
    return array_merge(parent::getVars(), $this->item->getVars());
  }
}
