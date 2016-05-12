<?php namespace SimplePaypal\Buttons;

use SimplePaypal\Common\Constants;

class CartUpload extends Cart
{
  protected static $allowed = array(
    'business',
    'discount_amount_cart',
    'discount_rate_cart',
    'handling_cart',
    'paymentaction',
    'shopping_url',
    'return'
  );
  protected $items = array(
    'cmd' => '_cart',
    'upload' => true,
  );
  protected $cartItems = array();

  protected function setDefaults()
  {
    $this->set('currency_code', $this->config->getCurrency());
    $this->set('business', $this->config->getBusinessId());
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

  public function createInnerHtml($formatted = true)
  {
    $sep = $formatted ? PHP_EOL : '';
    $html = '';
    foreach ($this->toArray() as $key => $value) {
      $html .= $this->createInputTag($key, $value) . $sep;
    }
    $counter = 1;
    foreach ($this->getItems() as $item) {
      foreach ($item->toArray() as $key => $value) {
        $html .= $this->createInputTag($key . '_' . $counter, $value) . $sep;
      }
      $counter++;
    }
    return $html;
  }

}
