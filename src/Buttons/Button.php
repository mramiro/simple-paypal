<?php namespace SimplePaypal\Buttons;

use Twig_Environment;
use Twig_Loader_Filesystem;
use SimplePaypal\Common\VarCollection;

abstract class Button extends VarCollection
{
  protected static $allowed = array(
    'cmd',
    'notify_url',
    'bn'
  );
  protected $renderer;
  protected $formAction;
  protected $formTarget = '_blank';
  protected $buttonType = '';
  protected $buttonLabel = 'Pay with {paypal}';
  protected $buttonSize = 'medium';
  protected $buttonStyle = 'blue';

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

  public function setLabel($label)
  {
    $this->buttonLabel = $label;
  }

  public function getLabel()
  {
    return $this->buttonLabel;
  }

  public function setStyle($style)
  {
    $this->buttonStyle = $style;
  }

  public function setSize($size)
  {
    $this->buttonSize = $size;
  }

  public function render($size = null, $style = null)
  {
    if (!isset($this->renderer)) {
      $this->renderer = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__.'/template'));
    }
    $replace = '<span class="sp-button-wordmark">PayPal</span>';
    $params = array(
      'action' => $this->formAction,
      'target' => $this->formTarget,
      'vars' => $this->getVars(),
      'size' => $size ? : $this->buttonSize,
      'style' => $style ? : $this->buttonStyle,
      'label' => preg_replace('/\{paypal\}/', $replace, $this->getLabel())
    );
    return $this->renderer->render('button.twig', $params);
  }

  public function __toString()
  {
    return $this->render();
  }
}
