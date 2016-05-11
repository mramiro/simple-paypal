<?php namespace SimplePaypal\Common;

interface ConfigResolver
{
  public function getEndpoint();
  public function getCurrency();
}
