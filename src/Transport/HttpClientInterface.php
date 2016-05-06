<?php namespace SimplePaypal\Transport;

interface HttpClientInterface
{
  public function get($url, array $options = array());
  public function post($url, array $options = array());
}
