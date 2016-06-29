<?php

use SimplePaypal\Manager;
use SimplePaypal\Common\Constants;

class ManagerTest extends PHPUnit_Framework_TestCase
{
  public function testConfigurationWorks()
  {
    // Test default values
    $manager = new Manager();
    $this->assertEquals(Constants::ENDPOINT, $manager->getEndpoint());
    $this->assertNull($manager->getPdtToken());
    $this->assertInstanceOf('SimplePaypal\Http\HttpClientInterface', $manager->getHttpClient());
    $this->assertEquals(Constants::DEFAULT_CURRENCY, $manager->getCurrency());

    // Test custom values
    $httpClient = Mockery::mock('SimplePaypal\Http\HttpClientInterface');
    $manager = new Manager(array(
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

    $t = $manager->validatePdtTransaction('totallyFakeTransactionId');
    $this->assertFalse($t->isSuccessful());
    $transactionErrors = $t->getErrors();
    $this->assertGreaterThan(0, $transactionErrors);
    // Paypal returns error 4002 when the transaction id (tx) is invalid
    $this->assertEquals(4002, $transactionErrors[0]);
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
