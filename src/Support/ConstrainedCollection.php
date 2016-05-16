<?php namespace SimplePaypal\Support;

abstract class ConstrainedCollection extends Collection
{
  public function set($key, $value)
  {
    if ($this->canBeSet($key)) {
      return parent::set($key, $value);
    }
    throw new \UnexpectedValueException("Collection does not allow setting key: [$key]");
  }

  protected abstract function canBeSet($key);
}
