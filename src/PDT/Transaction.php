<?php namespace SimplePaypal\PDT;

use SimplePaypal\Support\NvpCollection;

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
      parent::__construct($items);
    }
    else {
      $this->parseErrors($items);
    }
  }

  public static function fromResponseText($responseText)
  {
    $lines = explode(static::LINE_SEPARATOR, trim($responseText));
    $success = array_shift($lines) == static::SUCCESS;
    return new static($success, implode(static::LINE_SEPARATOR, $lines));
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
    if ($this->isSuccessful()) {
      return static::SUCCESS . static::LINE_SEPARATOR . parent::__toString();
    }
    else {
      return static::FAIL . static::LINE_SEPARATOR . $this->renderErrors();
    }
  }

}
