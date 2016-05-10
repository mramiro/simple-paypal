<?php

use SimplePaypal\Buttons\CartUploadButton;
use SimplePaypal\Cart\Item;

class CartUploadButtonTest extends PHPUnit_Framework_TestCase
{
  public function testFormRendering()
  {
    $cart = new CartUploadButton();
    $cart->addItem(new Item(101, 'a dog', 34.99))
      ->addItem(new Item(102, 'a cat', 28.49))
      ->addItem(new Item(103, 'a mouse', 9.99));
    echo $cart;
  }
}
