<?php

use SimplePaypal\PDT\Transaction;

class TransactionTest extends PHPUnit_Framework_TestCase
{
  private $successResponse;
  private $failResponse;

  public function setUp()
  {
    $this->successResponse = file_get_contents(__DIR__.'/successResponse.txt');
    $this->failResponse = file_get_contents(__DIR__.'/failResponse.txt');
  }
  public function testCreationFromString()
  {
    // Test a successful reply
    $t = Transaction::fromResponseText($this->successResponse);
    $this->assertTrue($t->isSuccessful());
    $this->assertEquals(10.5, $t->keynumber3);
    $this->assertEquals('Ms. &y(ampersandy) is the nicest puppy ever', $t->urlencoded);
    $this->assertEquals(trim($this->successResponse), (string)$t);

    // Test a failure reply
    $t = Transaction::fromResponseText($this->failResponse);
    $this->assertFalse($t->isSuccessful());
    $this->assertGreaterThan(0, $t->getErrors());
    $this->assertEquals(trim($this->failResponse), (string)$t);
  }
}
