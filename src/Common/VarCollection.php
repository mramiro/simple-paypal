<?php namespace SimplePaypal\Common;

use SimplePaypal\Support\ConstrainedCollection;

class VarCollection extends ConstrainedCollection
{
  protected static $allowed = array();

  public function getVars()
  {
    return $this->items;
  }

  protected function getAllowedVars()
  {
    return static::$allowed;
  }

  protected function canBeSet($key)
  {
    return in_array($key, $this->getAllowedVars());
  }
}
