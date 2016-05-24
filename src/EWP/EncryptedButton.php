<?php namespace SimplePaypal\EWP;

use SimplePaypal\Html\Button;

class EncryptedButton extends Button
{
  protected static $allowed = array(
    'cmd',
    'encrypted'
  );
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

  public function setInnerButton(Button $btn)
  {
    $this->innerButton = $btn;
  }

  public function encrypt(Encryptor $encryptor)
  {
    $this->encrypted = $encryptor->encrypt($this->varsToString());
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
