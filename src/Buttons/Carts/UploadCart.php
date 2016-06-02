<?php namespace SimplePaypal\Buttons\Carts;

use SimplePaypal\Common\Item;
use SimplePaypal\Buttons\CheckoutPage;
use SimplePaypal\Buttons\AutomaticFillout;

final class UploadCart extends Base
{
  protected static $allowed = array(
    'upload'
  );
  protected $buttonLabel = "Check out with {paypal}";
  protected $cartItems = array();

  protected function getAllowedVars()
  {
    return array_merge(parent::getAllowedVars(),
      CheckoutPage::$allowed,
      AutomaticFillout::$allowed
    );
  }

  public function __construct(array $items = array())
  {
    parent::__construct($items);
    $this->upload = true;
  }

  public function getItems()
  {
    return $this->cartItems;
  }

  public function addItem(Item $item)
  {
    $this->cartItems[] = $item;
    return $this;
  }

  public function setItems(array $items)
  {
    $this->cartItems = array();
    return $this->addItems($items);
  }

  public function addItems(array $items)
  {
    foreach ($items as $item) {
      $this->addItem($item);
    }
    return $this;
  }

  public function getVars()
  {
    $vars = parent::getVars();
    $counter = 1;
    foreach ($this->getItems() as $item) {
      foreach ($item->toArray() as $key => $value) {
        $vars[$key . '_' . $counter] = $value;
      }
      $counter++;
    }
    return $vars;
  }

}
