<?php namespace SimplePaypal\Support;

abstract class ConstrainedCollection extends Collection
{
  protected static $throwsExceptions = true;

  public function set($key, $value)
  {
    if ($this->canBeSet($key)) {
      return parent::set($key, $value);
    }
    if (static::$throwsExceptions) {
      throw new \UnexpectedValueException("Collection does not allow setting key: [$key]");
    }
  }

  public static function conform()
  {
    static::$throwsExceptions = false;
  }

  public static function complain()
  {
    static::$throwsExceptions = true;
  }

  protected abstract function canBeSet($key);
}
