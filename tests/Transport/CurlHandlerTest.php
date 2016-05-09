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

  public function testPost()
  {
    $instance = new CurlHandler();
    $someMarkdown = <<< MD
# Title
Lorem ipsum dolor sit amet, consectetur adipisicing elit
MD;
    $params = json_encode(array(
      'text' => $someMarkdown,
      'mode' => 'markdown'
    ));
    $response = $instance->post('https://api.github.com/markdown', $params);
    $this->assertContains('<h1>', $response);
  }
}
