<?php namespace SimplePaypal\Http;

class NodejsHandler extends ExternalHandler
{
  protected function getCommandName()
  {
    return 'node';
  }

  protected function buildArgs(array $config)
  {
    return __DIR__ . '/client.js' . ' ' . escapeshellarg(json_encode($config));
  }
}
