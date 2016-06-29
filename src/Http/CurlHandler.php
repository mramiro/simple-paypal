<?php namespace SimplePaypal\Http;

class CurlHandler extends BaseHandler
{
  protected function request(array $options)
  {
    $curl = $this->newCurl($options['url']);
    if ($options['method'] == 'POST') {
      curl_setopt($curl, CURLOPT_POST, true);
      if (isset($options['data'])) {
        $data = is_array($options['data']) ? http_build_query($options['data']) : $options['data'];
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      }
    }
    return $this->executeCurl($curl);
  }

  protected function newCurl($url)
  {
    $ch = curl_init($url);
    $v = curl_version();
    $defaults = array(
      CURLOPT_USERAGENT => 'cURL/'.$v['version'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true
    );
    if ($this->debug) {
      $defaults[CURLOPT_SSL_VERIFYPEER] = false;
      $defaults[CURLOPT_SSL_VERIFYHOST] = false;
    }
    curl_setopt_array($ch, $defaults);
    return $ch;
  }

  protected function executeCurl($curlHandler)
  {
    $response = curl_exec($curlHandler);
    curl_close($curlHandler);
    if ($response === false) {
      throw new HttpException();
    }
    return $response;
  }

  public static function checkCompatibility()
  {
    return defined('CURL_SSLVERSION_TLSv1_2');
  }

}
