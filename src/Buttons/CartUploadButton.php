<?php namespace SimplePaypal\Buttons;

use SimplePaypal\Support\Constants;
use SimplePaypal\Support\Collection;
use SimplePaypal\Cart\ShoppingCart;
use SimplePaypal\Cart\Item;

class CartUploadButton extends Button
{
  protected static $allowedVars = array(
    'business',
    'discount_amount_cart',
    'discount_rate_cart',
    'display',
    'handling_cart',
    'paymentaction',
    'shopping_url',
  );
  protected $variables = array(
    'cmd' => '_cart',
    'upload' => true,
    'display' => true,
    'currency_code' => Constants::DEFAULT_CURRENCY
  );
  protected $items;

  public function __construct($variables = array())
  {
    $this->items = new Collection();
    if (is_array($variables) && count($variables) > 0) {
      $this->setVars($variables);
    }
  }

  public function getItems()
  {
    return $this->items;
  }

  public function addItem(Item $item)
  {
    $this->items->add($item);
    return $this;
  }

  public function setItems(array $items)
  {
    foreach ($items as $item) {
      $this->addItem($item);
    }
    return $this;
  }

  public function setPayPalId($id)
  {
    return $this->setVar('business', $id);
  }

  public function setCurrency($currencyCode)
  {
    return $this->setVar('currency_code', $currencyCode);
  }

  public function setDiscount($discount, $relative = false)
  {
    return $relative ?
      $this->setVar('discount_rate_cart', $discount) :
      $this->setVar('discount_amount_cart', $discount);
  }

  public function setHandlingFee($fee)
  {
    return $this->setVar('handling_cart', $fee);
  }

  public function setPaymentAction($action)
  {
    return $this->setVar('paymentaction', $action);
  }

  public function setShoppingUrl($url)
  {
    return $this->setVar('shopping_url', $action);
  }

  public function setDisplayability($display = true)
  {
    return $this->setVar((bool)$display);
  }

  public function __toString()
  {
    $html = '';
    foreach ($this->getVars() as $key => $value) {
      $html .= $this->createInput($key, $value) . "\n";
    }
    $counter = 1;
    foreach ($this->getItems() as $item) {
      foreach ($item as $key => $value) {
        $html .= $this->createInput($key . '_' . $counter, $value)."\n";
      }
      $counter++;
    }
    return $html;
  }
}
