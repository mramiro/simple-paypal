<?php namespace SimplePaypal\Support;

class NvpCollection extends Collection
{
  const LINE_SEPARATOR = "\n";
  const PAIR_SEPARATOR = '=';

  public function __construct($items = array())
  {
    if (is_string($items)) {
      $items = $this->parseString($items);
    }
    parent::__construct($items);
  }

  protected function parseString($str)
  {
    $items = array();
    foreach (explode(static::LINE_SEPARATOR, $str) as $line) {
      if ($line = trim($line)) {
        list($k, $v) = $this->decodePair($line);
        $items[$k] = $v;
      }
    }
    return $items;
  }

  protected function decodePair($str)
  {
    list($key, $value) = explode(static::PAIR_SEPARATOR, urldecode($str));
    $a = str_getcsv($value);
    return array($key, $a[0]);
  }

  protected function encodePair($key, $value)
  {
    $key = rawurlencode($key);
    $value = rawurlencode($value);
    return $key . static::PAIR_SEPARATOR . $value;
  }

  public function __toString()
  {
    $items = array();
    foreach ($this->items as $key => $value) {
      $items[] = $this->encodePair($key, $value);
    }
    return implode(static::LINE_SEPARATOR, $items);
  }

}
