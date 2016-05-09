<?php namespace SimplePaypal\Transport;

interface HttpClientInterface
{
  public function get($url, $queryString = null, array $options = array());
  public function post($url, array $data = array(), array $options = array());
}
