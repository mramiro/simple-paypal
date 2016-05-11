<?php namespace SimplePaypal\Buttons;

use SimplePaypal\Common\Constants;
use SimplePaypal\Support\Collection;

class CartUpload extends Cart
{
  protected static $allowed = array(
    'business',
    'discount_amount_cart',
    'discount_rate_cart',
    'display',
    'handling_cart',
    'paymentaction',
    'shopping_url',
  );
  protected $items = array(
    'cmd' => '_cart',
    'upload' => true,
    'display' => true,
    'currency_code' => Constants::DEFAULT_CURRENCY
  );
  protected $cartItems;

  public function __construct($variables = array())
  {
    $this->cartItems = new Collection();
    if (is_array($variables) && count($variables) > 0) {
      $this->setVars($variables);
    }
  }

  public function getItems()
  {
    return $this->cartItems;
  }

  public function addItem(Item $item)
  {
    $this->cartItems->add($item);
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
    return $this->set('business', $id);
  }

  public function setCurrency($currencyCode)
  {
    return $this->set('currency_code', $currencyCode);
  }

  public function setDiscount($discount, $relative = false)
  {
    return $relative ?
      $this->set('discount_rate_cart', $discount) :
      $this->set('discount_amount_cart', $discount);
  }

  public function setHandlingFee($fee)
  {
    return $this->set('handling_cart', $fee);
  }

  public function setPaymentAction($action)
  {
    return $this->set('paymentaction', $action);
  }

  public function setShoppingUrl($url)
  {
    return $this->set('shopping_url', $action);
  }

  public function setDisplayability($display = true)
  {
    return $this->set('display', (bool)$display);
  }

  public function toHtmlForm($whitespaced = true)
  {
    $sep = $whitespaced ? PHP_EOL : '';
    $html = '';
    foreach ($this->toArray() as $key => $value) {
      $html .= $this->createInput($key, $value) . $sep;
    }
    $counter = 1;
    foreach ($this->getItems() as $item) {
      foreach ($item->toArray() as $key => $value) {
        $html .= $this->createInput($key . '_' . $counter, $value) . $sep;
      }
      $counter++;
    }
    return $html;
  }

  public function __toString()
  {
    return $this->toHtmlForm(false);
  }
}
