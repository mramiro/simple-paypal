<?php namespace SimplePaypal\Http;

interface HttpClientInterface
{
  public function get($url, $queryString = null);
  public function post($url, $data = null);
}
