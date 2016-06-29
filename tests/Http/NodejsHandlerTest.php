<?php

require_once __DIR__.'/HandlerTestBase.php';

use SimplePaypal\Http\NodejsHandler;

class NodejsHandlerTest extends HandlerTestBase
{
  protected function getInstance()
  {
    return new NodejsHandler(true);
  }
}
