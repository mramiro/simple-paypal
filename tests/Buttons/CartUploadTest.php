<?php

use SimplePaypal\Buttons\CartUpload;
use SimplePaypal\Buttons  \Item;

class CartUploadTest extends PHPUnit_Framework_TestCase
{
  public function testFormRendering()
  {
    $cart = new CartUpload();
    $cart->addItem(new Item(101, 'a dog', 34.99))
      ->addItem(new Item(102, 'a cat', 28.49))
      ->addItem(new Item(103, 'a mouse', 9.99));
    echo $cart;
  }
}
