<?php namespace SimplePaypal\Http;

use RuntimeException;

class NodejsHandler extends ExternalHandler
{
  protected static $path;

  protected function getCommand()
  {
    if (!isset(static::$path)) {
      $finder = strtoupper(substr(php_uname('s'), 0, 3)) == 'WIN' ? 'where' : 'which';
      exec("$finder node", $output, $return);
      if ($return != 0 || !$output) {
        throw new RuntimeException("Node.js not found.");
      }
      static::$path = $output[0];
    }
    return static::$path;
  }

  protected function buildArgs(array $config)
  {
    if ($this->debug) {
      $config['debug'] = true;
    }
    return __DIR__ . '/client.js' . ' ' . escapeshellarg(json_encode($config));
  }
}
