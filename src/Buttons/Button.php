<?php namespace SimplePaypal\Buttons;

use SimplePaypal\Common\ConfigResolver;
use SimplePaypal\Support\ConstrainedCollection;

abstract class Button extends ConstrainedCollection
{
  protected static $allowed = array(
    'cmd',
    'notify_url',
    'bn'
  );
  protected $config;

  public function __construct(ConfigResolver $config, array $vars = array())
  {
    $this->config = $config;
    $this->setDefaults();
    parent::__construct($vars);
  }

  protected function setDefaults() {}

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

  protected function createInputTag($name, $value, $type="hidden")
  {
    return "<input type=\"$type\" name=\"$name\" value=\"$value\">";
  }

  public function __toString()
  {
    return $this->toHtmlForm(false);
  }

  public function toHtmlForm($formatted = true)
  {
    $sep = $formatted ? PHP_EOL : '';
    $html = "<form method=\"POST\" action=\"{$this->config->getEndpoint()}\">" . $sep;
    $html .= $this->createInnerHtml($formatted);
    $html .= $this->createInputTag('submit', 'Pay with Paypal', 'submit') . $sep;
    $html .= "</form>";
    return $html;
  }

  public abstract function createInnerHtml($formatted = true);

}
