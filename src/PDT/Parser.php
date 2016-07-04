<?php namespace SimplePaypal\PDT;

use SimplePaypal\Common\Transaction;

class Parser
{
  const LINE_SEPARATOR = "\n";
  const PAIR_SEPARATOR = '=';
  const SUCCESS = 'SUCCESS';
  const FAILURE = 'FAIL';

  public static function parse($string)
  {
    $data = '';
    list($status, $data) = explode(static::LINE_SEPARATOR, trim($string), 2);
    return $status == static::SUCCESS ? static::parseItems($data) : static::parseError($data);
  }

  protected static function parseItems($str)
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
    $value = '';
    list($key, $value) = explode(static::PAIR_SEPARATOR, urldecode($str));
    $a = str_getcsv($value);
    return array($key, $a[0]);
  }

  protected static function parseError($str)
  {
    if (preg_match('/Error:\s+(\d+)/', $str, $match)) {
      return (string)$match[1];
    }
  }

}
