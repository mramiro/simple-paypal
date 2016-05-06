<?php namespace SimplePaypal\Transport;

class CurlHandler implements HttpClientInterface
{
  protected function newCurl($url)
  {
    $ch = curl_init($url);
    $v = curl_version();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'cURL/'.$v['version']);
    return $ch;
  }

  public function get($url, array $options = array())
  {
    $ch = $this->newCurl($url);
    return $this->executeCurl($ch);
  }

  public function post($url, array $options = array())
  {
    $ch = $this->newCurl($url);
    if (isset($options['postData'])) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $options['postData']);
    }
    return $this->executeCurl($ch);
  }

  protected function executeCurl($curlHandler)
  {
    $response = curl_exec($curlHandler);
    curl_close($curlHandler);
    return $response;
  }

}
