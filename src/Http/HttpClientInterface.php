<?php namespace SimplePaypal\Http;

interface HttpClientInterface
{
  public function get($url, $queryString = null, array $options = array());
  public function post($url, $data = null, array $options = array());
}
