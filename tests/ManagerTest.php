<?php

use SimplePaypal\Manager;
use SimplePaypal\Common\Constants;
use SimplePaypal\Common\Types\Currency;

class ManagerTest extends PHPUnit_Framework_TestCase
{
  public function testConfigurationWorks()
  {
    // Test default values
    $manager = new Manager();
    $this->assertEquals(Constants::ENDPOINT, $manager->getEndpoint());
    $this->assertNull($manager->getPdtToken());
    $this->assertInstanceOf('SimplePaypal\Transport\CurlHandler', $manager->getHttpClient());
    $this->assertEquals(Constants::DEFAULT_CURRENCY, $manager->getCurrency());

    // Test invalid values
    $this->expectException('UnexpectedValueException');
    $manager->setCurrency('WTF'); // Invalid currency code.

    // Test custom values
    $httpClient = Mockery::mock('SimplePaypal\Transport\HttpClientInterface');
    $manager = new Manager(array(
      'pdt_token' => 'dummy',
      'http_client' => $httpClient,
      'currency' => Currency::MEXICAN_PESO
    ), true);
    $this->assertContains('sandbox.paypal.com', $manager->getEndpoint());
    $this->assertEquals('dummy', $manager->getPdtToken());
    $this->assertEquals($httpClient, $manager->getHttpClient());
    $this->assertEquals(Currency::MEXICAN_PESO, $manager->getCurrency());
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
    $this->assertInstanceOf('SimplePaypal\Html\Carts\UploadCart', $cart);
    $this->assertEquals($manager->getBusinessId(), $cart->business);
  }

  public function tearDown()
  {
    Mockery::close();
  }
}
