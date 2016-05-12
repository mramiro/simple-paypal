<?php

use SimplePaypal\Buttons\CartUpload;
use SimplePaypal\Buttons\Item;

class CartUploadTest extends PHPUnit_Framework_TestCase
{
  private $configResolver, $data;

  public function setUp()
  {
    $this->data = json_decode(file_get_contents(__DIR__.'/CartUploadTest.json'));
    $config = $this->data->config;
    $this->configResolver = Mockery::mock('SimplePaypal\Common\ConfigResolver', array(
      'getEndpoint' => $config->endpoint,
      'getCurrency' => $config->currency,
      'getBusinessId' => $config->business
    ));
  }

  public function testFormRendering()
  {
    $items = array();
    foreach ($this->data->items as $item) {
      $new = new Item($item->id, $item->name, $item->price);
      if (isset($item->qty)) {
        $new->setQuantity($item->qty);
      }
      $items[] = $new;
    }
    $expected = file_get_contents(__DIR__.'/CartUploadTest.txt');
    $cart = new CartUpload($this->configResolver);
    $cart->addItems($items);
    $this->assertEquals(trim($expected), $cart->toHtmlForm());
  }

  public function tearDown()
  {
    Mockery::close();
  }
}
