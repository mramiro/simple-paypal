<?php namespace SimplePaypal\Http;

use RuntimeException;

class NodejsHandler extends ExternalHandler
{
  protected static $path;

  protected function getCommand()
  {
    if (($cmd = static::getNode()) === false) {
      throw new RuntimeException("Node.js not found.");
    }
    return $cmd;
  }

  protected static function getNode()
  {
    if (!isset(static::$path)) {
      $finder = strtoupper(substr(php_uname('s'), 0, 3)) == 'WIN' ? 'where' : 'which';
      exec("$finder node", $output, $return);
      if ($return != 0 || !$output) {
        return false;
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

  public static function checkCompatibility()
  {
    return static::getNode() != false;
  }
}
