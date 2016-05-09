<?php namespace SimplePaypal\Transport;

class CurlHandler implements HttpClientInterface
{
  protected function newCurl($url = null, array $options = array())
  {
    $ch = curl_init();
    if (!is_null($url)) {
      curl_setopt($ch, CURLOPT_URL, $url);
    }
    $v = curl_version();
    $defaults = array(
      CURLOPT_USERAGENT => 'cURL/'.$v['version'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true
    );
    curl_setopt_array($ch, array_replace($defaults, $options));
    return $ch;
  }

  public function get($url, $queryString = null, array $options = array())
  {
    if ($queryString) {
      if (is_array($queryString)) {
        $queryString = http_build_query($queryString);
      }
      $url = strpos('?', $url) === false ?
        $url . '?' . $queryString :
        $url . '&' . $queryString;
    }
    $ch = $this->newCurl($url, $options);
    return $this->executeCurl($ch);
  }

  public function post($url, array $data = array(), array $options = array())
  {
    $ch = $this->newCurl($url, $options);
    curl_setopt($ch, CURLOPT_POST, true);
    if (count($data) > 0) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
