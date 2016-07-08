<?php

use SimplePaypal\SimplePaypal;
use SimplePaypal\Common\Constants;
use SimplePaypal\Common\Transaction;

class SimplePaypalTest extends PHPUnit_Framework_TestCase
{
  public function testConfigurationWorks()
  {
    // Test default values
    $manager = new SimplePaypal();
    $this->assertEquals(Constants::ENDPOINT, $manager->getEndpoint());
    $this->assertNull($manager->getPdtToken());
    $this->assertInstanceOf('SimplePaypal\Http\HttpClientInterface', $manager->getHttpClient());
    $this->assertEquals(Constants::DEFAULT_CURRENCY, $manager->getCurrency());

    // Test custom values
    $httpClient = Mockery::mock('SimplePaypal\Http\HttpClientInterface');
    $manager = new SimplePaypal(array(
      'pdt_token' => 'dummy',
      'http_client' => $httpClient,
      'currency' => 'MXN'
    ), true);
    $this->assertContains('sandbox.paypal.com', $manager->getEndpoint());
    $this->assertEquals('dummy', $manager->getPdtToken());
    $this->assertEquals($httpClient, $manager->getHttpClient());
    $this->assertEquals('MXN', $manager->getCurrency());
  }

  public function testPdtTransactionValidation()
  {
    $manager = newManagerForDebug();

    $t = $manager->validateTransactionPdt(new Transaction('totallyFakeTransactionId'));
    $this->assertFalse($t->isValid());
    // Paypal returns error 4002 when the transaction id (tx) is invalid
    $this->assertEquals(4002, $t->error);
  }

  public function testCreateUploadCartButton()
  {
    $manager = newManagerForDebug();
    $cart = $manager->createUploadCartButton();
    $this->assertInstanceOf('SimplePaypal\Buttons\Carts\UploadCart', $cart);
    $this->assertEquals($manager->getBusinessId(), $cart->business);
  }

  public function tearDown()
  {
    Mockery::close();
  }
}
