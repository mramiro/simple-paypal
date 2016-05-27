<?php namespace SimplePaypal\Support;

use Closure;
use BadMethodCallException;

class Configurable
{

  public function __construct(array $options = array())
  {
    $this->configure($options);
  }

  /**
   * Returns an array with the default values to be assigned to configuration options if such
   * options are not provided at instantiation time
   *
   * The array must be in the form:
   * [ 'config_item_1' => DEFAULT_VALUE_1, 'config_item_2' => DEFAULT_VALUE_2, ... ]
   * DEFAULT_VALUE_N can be any expression that evaluates to a non null value. If a closure is
   * provided, it will be executed during instantiation and its return value will be assigned to
   * the correspoding option.
   * Such closures should have the signature: function(array $options), where $options the array
   * containing all the configuration options passed to configure()
   *
   * @return array An array with the config options as keys and their default values as values
   */
  protected function defaults()
  {
    return array();
  }

  protected function configure(array $options)
  {
    foreach (array_merge($this->defaults(), $options) as $key => $value) {
      $property = static::camelCase($key);
      // If there's a concrete setConfigurationOption() method defined, we use it instead of doing
      // a simple assignment.
      // Note: We use method_exists() instead of is_callable() so __call isn't considered
      $method = 'set'.ucfirst($property);
      if (method_exists($this, $method)) {
        $this->$method($this->dynamicValue($value, $options));
      }
      // Else, a property is only set if it is explicitly defined in the inherited class...
      else if (property_exists($this, $property)) {
        $this->$property = $this->dynamicValue($value, $options);
      }
    }
  }

  // Manages "getConfigurationOption()" and "setConfigurationOption($value)"
  public function __call($method, $args)
  {
    $property = lcfirst(substr($method, 3));
    if (property_exists($this, $property)) {
      $action = substr($method, 0, 3);
      if ($action == 'get') {
        return $this->$property;
      }
      if ($action == 'set') {
        $this->$property = $args[0];
        return $this;
      }
    }
    throw new BadMethodCallException;
  }

  private static function camelCase($str)
  {
    return lcfirst(str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $str))));
  }

  private function dynamicValue($expression, array $options = null)
  {
    return $expression instanceof Closure ? $expression($options) : $expression;
  }

}
