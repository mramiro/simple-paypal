<?php namespace SimplePaypal\Http;

use RuntimeException;

abstract class ExternalHandler implements HttpClientInterface
{
  protected static $cmd;

  public function get($url, $queryString = null, array $options = null)
  {
    if (!is_null($queryString)) {
      $params = is_array($queryString)
        ? http_build_query($queryString)
        : $queryString;
      $url .= strpos($url, '?') === false  ? '?' : '&' . $params;
    }
    $config = array(
      'url' => $url,
      'method' => 'GET'
    );
    return $this->run($config);
  }

  public function post($url, $data = null, array $options = null)
  {
    $config = array(
      'url' => $url,
      'method' => 'POST'
    );
    if (!is_null($data)) {
      $config['data'] = $data;
    }
    return $this->run($config);
  }

  protected function getCommand($name)
  {
    if (!isset(static::$cmd)) {
      $finder = strtoupper(substr(php_uname('s'), 0, 3)) == 'WIN' ? 'where' : 'which';
      exec($finder . ' ' . escapeshellarg($name), $output, $return);
      if ($return != 0) {
        throw new RuntimeException("Command $name not found on system.");
      }
      static::$cmd = $output[0];
    }
    return static::$cmd;
  }

  protected function run(array $config)
  {
    $cmd = $this->getCommand($this->getCommandName());
    $args = $this->buildArgs($config);
    exec($cmd . ' ' . $args, $output, $return);
    if ($return != 0) {
      throw new HttpException();
    }
    return implode("\n", $output);
  }

  protected abstract function getCommandName();

  protected abstract function buildArgs(array $config);

}
