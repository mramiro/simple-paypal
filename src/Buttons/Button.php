<?php namespace SimplePaypal\Buttons;

abstract class Button
{
  protected static $allowedVars = array(
    'cmd',
    'notify_url',
    'bn'
  );

  protected function getAllowedVars()
  {
    return self::$allowedVars;
  }

  public function setVar($key, $value)
  {
    if ($this->varCanBeSet($key)) {
      $this->$variables[$key] = $value;
    }
    return $this;
  }

  public function getVar($key)
  {
    return array_key_exists($key, $this->$variables) ? $this->$variables[$key] : null;
  }

  public function setVars(array $variables)
  {
    foreach ($variables as $key => $value) {
      $this->setVar($key, $value);
    }
    return $this;
  }

  public function getVars()
  {
    return $this->variables;
  }

  protected function varCanBeSet($key)
  {
    return in_array($key, $this->getAllowedVars());
  }

  public abstract function __toString();

  protected function createInput($name, $value, $type="hidden")
  {
    return "<input type=\"$type\" name=\"$name\" value=\"$value\">";
  }
}
