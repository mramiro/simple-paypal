<?php namespace SimplePaypal\Html;

use SimplePaypal\Support\ConstrainedCollection;

class VarCollection extends ConstrainedCollection
{
  protected static $allowed = array();

  protected function getAllowedVars()
  {
    return static::$allowed;
  }

  protected function canBeSet($key)
  {
    return in_array($key, $this->getAllowedVars());
  }
}
