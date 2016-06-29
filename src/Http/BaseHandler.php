<?php namespace SimplePaypal\Http;

abstract class BaseHandler implements HttpClientInterface
{
  protected $debug;

  public function __construct($debug = false)
  {
    $this->debug = $debug;
  }

  public function get($url, $queryString = null)
  {
    if (!is_null($queryString)) {
      $params = is_array($queryString)
        ? http_build_query($queryString)
        : $queryString;
      $url .= (strpos($url, '?') === false  ? '?' : '&') . $params;
    }
    $options = array(
      'url' => $url,
      'method' => 'GET'
    );
    return $this->request($options);
  }

  public function post($url, $data = null)
  {
    $options = array(
      'url' => $url,
      'method' => 'POST'
    );
    if (!is_null($data)) {
      $options['data'] = $data;
    }
    return $this->request($options);
  }

  protected abstract function request(array $options);

  protected abstract static function checkCompatibility();

}
