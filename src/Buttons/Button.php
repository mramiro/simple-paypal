<?php namespace SimplePaypal\Buttons;

use SimplePaypal\Support\ConstrainedCollection;

abstract class Button extends ConstrainedCollection
{
  protected static $allowed = array(
    'cmd',
    'notify_url',
    'bn'
  );

  protected function getAllowedVars()
  {
    $vars = static::$allowed;
    $current = get_class($this);
    while ($current) {
      $parent = get_parent_class($current);
      if (isset($parent::$allowed)) {
        $vars = array_unique(array_merge($parent::$allowed, $vars));
      }
      else {
        break;
      }
      $current = $parent;
    };
    return $vars;
  }

  protected function canBeSet($key)
  {
    return in_array($key, $this->getAllowedVars());
  }

  protected function createInput($name, $value, $type="hidden")
  {
    return "<input type=\"$type\" name=\"$name\" value=\"$value\">";
  }

  public abstract function __toString();
}
