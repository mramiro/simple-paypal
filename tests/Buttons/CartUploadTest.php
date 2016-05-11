<?php

use SimplePaypal\Buttons\CartUpload;
use SimplePaypal\Buttons\Item;

class CartUploadTest extends PHPUnit_Framework_TestCase
{
  private $configResolver;

  public function setUp()
  {
    $this->configResolver = Mockery::mock('SimplePaypal\Common\ConfigResolver', array(
      'getEndpoint' => 'https://dummy.endpoint.com',
      'getCurrency' => 'USD',
      'getBusinessId' => 'dummy@mail.com'
    ));
  }

  public function testFormRendering()
  {
    $cart = new CartUpload($this->configResolver);
    $cart->addItem(new Item(101, 'a dog', 34.99))
      ->addItem(new Item(102, 'a cat', 28.49))
      ->addItem(new Item(103, 'a mouse', 9.99));
    echo $cart;
  }

  public function tearDown()
  {
    Mockery::close();
  }
}
