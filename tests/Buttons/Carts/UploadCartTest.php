<?php

use SimplePaypal\Buttons\Carts\UploadCart;
use SimplePaypal\Buttons\Item;

class UploadCartTest extends PHPUnit_Framework_TestCase
{
  private $items, $config;

  public function setUp()
  {
    $data = json_decode(file_get_contents(__DIR__.'/UploadCartTest.json'));
    $this->items = $data->items;
    $this->config = $data->config;
  }

  public function testFormRendering()
  {
    $cart = new UploadCart();
    $cart->setFormAction($this->config->endpoint);
    $cart->currency_code = $this->config->currency;
    $cart->business = $this->config->business;
    $items = array();
    foreach ($this->items as $item) {
      $new = new Item($item->id, $item->name, $item->price);
      if (isset($item->qty)) {
        $new->quantity = $item->qty;
      }
      $items[] = $new;
    }
    $cart->addItems($items);
    $rendered = trim($cart->render());
    $this->assertStringStartsWith('<form', $rendered);
    $this->assertContains($this->items[0]->name, $rendered);
    $this->assertStringEndsWith('</form>', $rendered);
  }
}
