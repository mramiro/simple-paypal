<?php namespace SimplePaypal\IPN;

use SimplePaypal\Http\HttpClientInterface;
use SimplePaypal\Http\HttpException;

class Validator
{
  protected $client;
  protected $endpoint;

  public function __construct(HttpClientInterface $client, $endpoint)
  {
    $this->client = $client;
    $this->endpoint = $endpoint;
  }

  public function validate($postData = null)
  {
    if (is_null($postData)) {
      if (get_magic_quotes_gpc()) {
        array_map($_POST, function(&$v) {
          $v = stripslashes($v);
        });
      }
      $postData = $_POST;
    }
    if (is_array($postData)) {
      $postData = http_build_query($postData);
    }
    $postData = 'cmd=_notify-validate&' . $postData;

    $response = $this->client->post($this->endpoint, $postData);
    return trim($response) == 'VERIFIED' ? $postData : false;
  }

}
