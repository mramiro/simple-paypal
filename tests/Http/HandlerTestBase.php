<?php

abstract class HandlerTestBase extends PHPUnit_Framework_TestCase
{
  private $endpoint;

  protected abstract function getInstance();

  public function setUp()
  {
    $endpoint = 'https://';
    $endpoint .= isset($_ENV['TEST_SERVER_HOST']) ? $_ENV['TEST_SERVER_HOST'] : 'localhost';
    $endpoint .= ':';
    $endpoint .= isset($_ENV['TEST_SERVER_PORT']) ? $_ENV['TEST_SERVER_PORT'] : '8080';
    $this->endpoint = $endpoint;
  }

  public function testGet()
  {
    $instance = $this->getInstance();
    $response = $instance->get($this->endpoint . '/?dummy=10');
    $json = json_decode($response);
    $this->assertNotNull($json);
    $this->assertEquals(10, $json->dummy);
  }

  public function testPost()
  {
    $instance = $this->getInstance();
    $params = array(
      'text' => 'dummy',
      'number' => 25
    );
    $response = $instance->post($this->endpoint, $params);
    $json = json_decode($response);
    $this->assertNotNull($json);
    $this->assertEquals(25, $json->number);
  }
}
