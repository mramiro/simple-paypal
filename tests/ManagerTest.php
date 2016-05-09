<?php

use Mockery;
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

}
