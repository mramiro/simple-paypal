<?php namespace SimplePaypal\Support;

use Stringy\StaticStringy as S;

class Configurable
{
  protected static $options = array();

  public function __construct(array $options = array())
  {
    $this->setDefaults();
    $this->configure($options);
  }

  protected function setDefaults()
  {
    foreach (static::$options as $key => $value) {
      if (!is_null($value)) {
        $this->setConfigurationOption($key, $value);
      }
    }
  }

  protected function configure(array $options)
  {
    foreach ($options as $key => $value) {
      if (in_array($key, array_keys(static::$options))) {
        $this->setConfigurationOption($key, $value);
      }
    }
  }

  protected function setConfigurationOption($key, $value)
  {
    $camelized = S::camelize($key);
    $methodName = 'set'. ucfirst($camelized);
    if (method_exists($this, $methodName)) {
      return $this->$methodName($value);
    }
    $this->$camelized = $value;
  }

}
