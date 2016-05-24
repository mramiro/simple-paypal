<?php namespace SimplePaypal\Html;

abstract class Button extends VarCollection
{
  protected static $allowed = array(
    'cmd',
    'notify_url',
    'bn'
  );
  protected $formAction;
  protected $buttonType = '';

  protected function getAllowedVars()
  {
    $vars = static::$allowed;
    $current = get_class($this);
    while ($current) {
      $parent = get_parent_class($current);
      if (isset($parent::$allowed)) {
        $vars = array_merge($parent::$allowed, $vars);
      }
      else {
        break;
      }
      $current = $parent;
    };
    return $vars;
  }

  public function getVars()
  {
    return $this->items;
  }

  protected function createInputTag($name, $value, $type="hidden")
  {
    return "<input type=\"$type\" name=\"$name\" value=\"$value\">";
  }

  public function __toString()
  {
    return $this->toHtmlForm(false);
  }

  public function setFormAction($action)
  {
    $this->formAction = $action;
  }

  public function setBuildNotation($vendor, $country)
  {
    $this->bn = implode('_', array($vendor, $this->buttonType, 'WPS', $country));
  }

  public function toHtmlForm($formatted = true)
  {
    $sep = $formatted ? PHP_EOL : '';
    $html = "<form method=\"POST\" action=\"{$this->formAction}\">" . $sep;
    $html .= $this->createInnerHtml($formatted);
    $html .= $this->createInputTag('submit', 'Pay with Paypal', 'submit') . $sep;
    $html .= "</form>";
    return $html;
  }

  protected function createInnerHtml($formatted = true)
  {
    $sep = $formatted ? PHP_EOL : '';
    $html = '';
    foreach ($this->getVars() as $key => $value) {
      $html .= $this->createInputTag($key, $value) . $sep;
    }
    return $html;
  }

}
