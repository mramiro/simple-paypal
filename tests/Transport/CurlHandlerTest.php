<?php

use SimplePaypal\Transport\CurlHandler;

class CurlHandlerTest extends PHPUnit_Framework_TestCase
{
  public function testGet()
  {
    $instance = new CurlHandler;
    $response = $instance->get('https://api.github.com/users/HackerYou');
    $json = json_decode($response);
    $this->assertNotNull($json);
    $this->assertEquals($json->login, 'HackerYou');
  }
}
