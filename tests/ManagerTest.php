<?php

use SimplePaypal\Manager;
use SimplePaypal\Support\Constants;

class ManagerTest extends PHPUnit_Framework_TestCase
{
  public function testConfigurationWorks()
  {
    // Test default values
    $manager = new Manager();
    $this->assertEquals(Constants::ENDPOINT, $manager->getEndpoint());
    $this->assertNull($manager->getPdtToken());
    $this->assertInstanceOf('SimplePaypal\Transport\CurlHandler', $manager->getHttpClient());

    // Test custom values
    $httpClient = Mockery::mock('SimplePaypal\Transport\HttpClientInterface');
    $manager = new Manager(array(
      'debug' => true,
      'pdt_token' => 'dummy',
      'http_client' => $httpClient
    ));
    $this->assertEquals(Constants::SANDBOX_ENDPOINT, $manager->getEndpoint());
    $this->assertEquals('dummy', $manager->getPdtToken());
    $this->assertEquals($httpClient, $manager->getHttpClient());
  }

  protected function newManager()
  {
    return new Manager(array(
      'debug' => true,
      'pdt_token' => getenv('PDT_TOKEN')
    ));
  }

  public function testPdtTransactionValidation()
  {
    $manager = $this->newManager();

    $t = $manager->validatePdtTransaction('totallyFakeTransactionId');
    $this->assertFalse($t->isSuccessful());
    $transactionErrors = $t->getErrors();
    $this->assertGreaterThan(0, $transactionErrors);
    // Paypal returns error 4002 when the transaction id (tx) is invalid
    $this->assertEquals(4002, $transactionErrors[0]);
  }
}
