<?php namespace SimplePaypal\Support;

use Closure;
use BadMethodCallException;
use Stringy\Stringy as S;

abstract class Configurable
{
  // Must return an array in the form:
  // [ 'config_item' => default_value, 'another_config_item' => null, ... ]
  // The keys should be in snake_case.
  // default_value can be any expression that evaluates to a value, even closures, wich would be
  // executed. Such closures should have the following signature: function($self). A null value
  // can be set for configuration options that do not have a default value
  protected abstract function getConfigOptions();

  // Every key in the array returned by getConfigOptions() must be mapped to a property with
  // the same name in camelCase, e.g.:
  // protected $configItem, $anotherConfigItem;

  public function __construct(array $options = array())
  {
    $this->setDefaults(array_keys($options));
    $this->configure($options);
  }

  protected function setDefaults(array $ignored)
  {
    foreach ($this->getConfigOptions() as $key => $value) {
      if (!in_array($key, $ignored) && !is_null($value)) {
        $this->setConfigValue($key, $value);
      }
    }
  }

  protected function configure(array $options)
  {
    foreach ($options as $key => $value) {
      if (in_array($key, array_keys($this->getConfigOptions()))) {
        $this->setConfigValue($key, $value);
      }
    }
  }

  protected function setConfigValue($key, $value)
  {
    $camelized = S::create($key)->camelize();
    $methodName = 'set'. $camelized->upperCaseFirst();
    // We use method_exists() instead of is_callable() so __call isn't considered, since it would
    // do exactly the same as the else block here, wich is setting the property value directly.
    // Therefore, this only goes through if there's en explicitly defined method (e.g. setMyVar()),
    // this is the case if you want override the default behavior or conform to an interface
    if (method_exists($this, $methodName)) {
      $value = $value instanceof Closure ? $value($this) : $value;
      return $this->$methodName($value);
    }
    // Also, a property is only set if it is explicitly defined in the inherited class
    else if (property_exists($this, $camelized)) {
      $value = $value instanceof Closure ? $value($this) : $value;
      $this->$camelized = $value;
    }
  }

  // Manages "getConfigItem()" and "setConfigItem($value)"
  public function __call($methodName, $args)
  {
    $methodName = S::create($methodName);
    $action = $methodName->first(3)->toLowerCase();
    if ($action == 'get') {
      $var = $methodName->slice(3)->lowerCaseFirst();
      return property_exists($this, $var) ? $this->$var : null;
    }
    if ($action == 'set') {
      $var = $methodName->slice(3)->lowerCaseFirst();
      if (property_exists($this, $var)) {
        $this->$var = $args[0];
        return $this;
      }
    }
    throw new BadMethodCallException;
  }

}
