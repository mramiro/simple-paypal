<?php

use SimplePaypal\Http\NodejsHandler;

class NodejsHandlerTest extends PHPUnit_Framework_TestCase
{
  public function testGet()
  {
    $instance = new NodejsHandler();
    $response = $instance->get('https://tlstest.paypal.com');
    $this->assertContains('PayPal_Connection_OK', $response);
  }
}
