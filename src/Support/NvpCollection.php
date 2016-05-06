<?php namespace SimplePaypal\Support;

class NvpCollection extends Collection
{
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
    foreach (explode("\n") as $line) {
      if ($line = trim($line)) {
        list($k, $v) = explode('=', $line);
        $items[$k] = urldecode($v);
      }
    }
    return $items;
  }

  public function __toString()
  {
    $quoted = array_map($this->items, function($item){
      return is_numeric($item) ? $item : '"'.$item.'"';
    });
    return implode("\n", $quoted);
  }

}
