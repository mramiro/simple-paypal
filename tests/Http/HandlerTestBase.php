<?php

abstract class HandlerTestBase extends PHPUnit_Framework_TestCase
{
  private $localServer = 'https://localhost:8080';

  protected abstract function getInstance();

  public function testGet()
  {
    $instance = $this->getInstance();
    $response = $instance->get($this->localServer . '/?dummy=10');
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
    $response = $instance->post($this->localServer, $params);
    $json = json_decode($response);
    $this->assertNotNull($json);
    $this->assertEquals(25, $json->number);
  }
}
