<?php namespace SimplePaypal\Common;

use SimplePaypal\Support\Collection;

class NvpCollection extends Collection
{
  const LINE_SEPARATOR = "\n";
  const PAIR_SEPARATOR = '=';

  public static function fromString($string)
  {
    return new static(static::parseString($string));
  }

  public function __construct($items = array())
  {
    if (is_string($items)) {
      $items = static::parseString($items);
    }
    parent::__construct($items);
  }

  protected static function parseString($str)
  {
    $items = array();
    foreach (explode(static::LINE_SEPARATOR, $str) as $line) {
      if ($line = trim($line)) {
        list($k, $v) = static::decodePair($line);
        $items[$k] = $v;
      }
    }
    return $items;
  }

  protected static function decodePair($str)
  {
    list($key, $value) = explode(static::PAIR_SEPARATOR, urldecode($str));
    $a = str_getcsv($value);
    return array($key, $a[0]);
  }

  protected static function encodePair($key, $value)
  {
    $key = rawurlencode($key);
    $value = rawurlencode($value);
    return $key . static::PAIR_SEPARATOR . $value;
  }

  public function __toString()
  {
    $items = array();
    foreach ($this->items as $key => $value) {
      $items[] = static::encodePair($key, $value);
    }
    return implode(static::LINE_SEPARATOR, $items);
  }

}
