<?php

require_once __DIR__.'/HandlerTestBase.php';

use SimplePaypal\Http\CurlHandler;

class CurlHandlerTest extends HandlerTestBase
{
  protected function getInstance()
  {
    return new CurlHandler(true);
  }
}
