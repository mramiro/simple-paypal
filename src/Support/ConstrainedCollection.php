<?php namespace SimplePaypal\Support;

abstract class ConstrainedCollection extends Collection
{
  public function set($key, $value)
  {
    if ($this->canBeSet($key)) {
      parent::set($key, $value);
    }
    return $this;
  }

  protected abstract function canBeSet($key);
}
