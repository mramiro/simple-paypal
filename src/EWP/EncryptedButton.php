<?php namespace SimplePaypal\EWP;

use SimplePaypal\Buttons\Button;

class EncryptedButton extends Button
{
  protected $buttonLabel = null;
  protected $certId;
  protected $innerButton;

  public function __construct($certId, Button $btn = null)
  {
    $this->cmd = '_s-xclick';
    $this->certId = $certId;
    if (!is_null($btn)) {
      $this->setInnerButton($btn);
    }
  }

  protected function getAllowedVars()
  {
    return array('cmd', 'encrypted');
  }

  final public function setBuildNotation($vendor, $country) {}

  public function setInnerButton(Button $btn)
  {
    $this->innerButton = $btn;
    return $this;
  }

  public function getLabel()
  {
    return isset($this->buttonLabel) ? $this->buttonLabel : $this->innerButton->getLabel();
  }

  public function encrypt(Encryptor $encryptor)
  {
    $this->encrypted = $encryptor->encrypt($this->varsToString());
    return $this;
  }

  private function varsToString()
  {
    $string = 'cert_id='.$this->certId;
    foreach ($this->innerButton->getVars() as $key => $value) {
      $string .= "\n$key=$value";
    }
    return $string;
  }

}
