<?php namespace SimplePaypal\PDT;

use SimplePaypal\Common\NvpCollection;

class Transaction extends NvpCollection
{
  const SUCCESS = 'SUCCESS';
  const FAIL = 'FAIL';

  protected $success = false;
  protected $errors = array();

  public function __construct($success, $items)
  {
    $this->success = $success;
    if ($success) {
      return parent::__construct($items);
    }
    $this->parseErrors($items);
  }

  public static function fromString($responseText)
  {
    $exploded = explode(static::LINE_SEPARATOR, trim($responseText), 2);
    $status = $exploded[0];
    $lines = isset($exploded[1]) ? $exploded[1] : '';
    return new static($status == static::SUCCESS, $lines);
  }

  public function isSuccessful()
  {
    return $this->success;
  }

  public function getErrors()
  {
    return $this->errors;
  }

  protected function parseErrors($str)
  {
    if (preg_match_all('/Error:\s+(\d+)/', $str, $matches)) {
      foreach ($matches[1] as $match) {
        $this->errors[] = $match;
      }
    }
  }

  protected function renderErrors()
  {
    $lines = array();
    foreach ($this->errors as $error) {
      $lines[] = "Error: $error";
    }
    return implode(static::LINE_SEPARATOR, $lines);
  }

  public function __toString()
  {
    return $this->isSuccessful()
      ? static::SUCCESS . static::LINE_SEPARATOR . parent::__toString()
      : static::FAIL . static::LINE_SEPARATOR . $this->renderErrors();
  }

  public function getItemsData()
  {
    $items = array();
    foreach ($this->items as $key => $value) {
      if (preg_match('/^([a-z_]+)([0-9]+)$/', $key, $m)) {
        $k = preg_replace('/_$/', '', $m[1]);
        $items[$m[2]][$k] = $value;
      }
    }
    return $items;
  }

}
