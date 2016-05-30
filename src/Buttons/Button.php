<?php namespace SimplePaypal\Buttons;

use Twig_Environment;
use Twig_Loader_Filesystem;

abstract class Button extends VarCollection
{
  const TEMPLATE_DIR = __DIR__.'/theme';

  protected static $allowed = array(
    'cmd',
    'notify_url',
    'bn'
  );
  protected $renderer;
  protected $formAction;
  protected $formTarget = '_blank';
  protected $buttonType = '';
  protected $buttonLabel = 'Pay with Paypal';
  protected $buttonSize = 'large';
  protected $buttonStyle = null;

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
    if (!isset($this->renderer)) {
      $this->renderer = new Twig_Environment(new Twig_Loader_Filesystem(static::TEMPLATE_DIR));
    }
    $params = array(
      'action' => $this->formAction,
      'target' => $this->formTarget,
      'vars' => $this->getVars(),
      'size' => $this->buttonSize,
      'style' => $this->buttonStyle,
      'label' => $this->buttonLabel,
    );
    return $this->renderer->render('template.twig', $params);
  }

  public function __toString()
  {
    return $this->toHtmlForm();
  }
}
