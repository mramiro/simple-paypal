<?php namespace SimplePaypal\Html;

abstract class Button extends VarCollection
{
  protected static $allowed = array(
    'cmd',
    'notify_url',
    'bn'
  );
  protected $renderer;
  protected $label = 'Pay with Paypal';
  protected $formAction;
  protected $formTarget = '_blank';
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

  public function setFormAction($action)
  {
    $this->formAction = $action;
  }

  public function setFormTarget($target)
  {
    $this->formTarget = $target;
  }

  public function setBuildNotation($vendor, $country)
  {
    $this->bn = implode('_', array($vendor, $this->buttonType, 'WPS', $country));
  }

  public function toHtmlForm()
  {
    ob_start();
    include __DIR__.'/template.php';
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function __toString()
  {
    return $this->toHtmlForm();
  }
}
