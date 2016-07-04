<?php

use SimplePaypal\PDT\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
  private $successResponse;
  private $failResponse;

  public function setUp()
  {
    $this->successResponse = file_get_contents(__DIR__.'/successResponse.txt');
    $this->failResponse = file_get_contents(__DIR__.'/failResponse.txt');
  }
  public function testSuccessfulResponse()
  {
    $data = Parser::parse($this->successResponse);
    $this->assertInternalType('array', $data);
    $this->assertEquals(10.5, $data['keynumber3']);
    $this->assertEquals('Ms. &y(ampersandy) is the nicest puppy ever', $data['urlencoded']);
  }

  public function testFailureResponse()
  {
    $data = Parser::parse($this->failResponse);
    $this->assertInternalType('string', $data);
    $this->assertEquals(4033, $data);
  }
}
