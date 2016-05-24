<?php namespace SimplePaypal\EWP;

class Encryptor
{
  protected $cert;
  protected $key;
  protected $paypalCert;

  protected $processor;

  public function __construct($cert, $key, $ppCert, PKCS7Processor $processor = null)
  {
    $this->setCertificate($cert, $key);
    $this->setPaypalCertificate($ppCert);
    $this->setProcessor(!is_null($processor) ? $processor : new OpensslProcessor());
  }

  public function setProcessor(PKCS7Processor $processor)
  {
    $this->processor = $processor;
  }

  public function setCertificate($cert, $privKey)
  {
    $this->cert = $this->readPEM($cert);
    $this->key = $this->parseKey($privKey);
  }

  public function setPayPalCertificate($cert)
  {
    $this->paypalCert = $this->readPEM($cert);
  }

  public function encrypt($plainText)
  {
    $signed = $this->processor->sign($plainText, $this->cert, $this->key);
    $encrypted = $this->processor->encrypt($signed, $this->paypalCert);
    return $encrypted;
  }

  protected function parseKey($key)
  {
    if (is_array($key)) {
      $key[0] = $this->readPEM($key[0]);
    }
    return $this->readPEM($key);
  }

  protected function readPEM($pem)
  {
    return is_readable($pem) ? file_get_contents($pem) : $pem;
  }

}
