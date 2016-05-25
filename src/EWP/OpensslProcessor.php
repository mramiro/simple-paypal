<?php namespace SimplePaypal\EWP;

// Adapted from http://blog.scrobbld.com/paypal/protecting-your-payments-with-ewp/

class OpensslProcessor implements PKCS7Processor
{
  protected $tempFileDirectory;

  public function __construct($tempFileDir = '')
  {
    if (!extension_loaded('openssl')) {
      throw new EWPException(__CLASS__.': OpenSSL module not present');
    }
    $this->tempFileDirectory = $tempFileDir ? : sys_get_temp_dir();
  }

  public function sign($message, $certificate, $privateKey)
  {
    $key = $this->checkPrivateKey($certificate, $privateKey);

    $plain = $this->newTempFile();
    $signed = $this->newTempFile();

    $ptr = fopen($plain, 'wb');
    fwrite($ptr, $message);
    fclose($ptr);

    if (openssl_pkcs7_sign($plain, $signed, $certificate, $key, array(), PKCS7_BINARY)) {
      $data = $this->stripHeader(file_get_contents($signed));
      @unlink($plain);
      @unlink($signed);
      return base64_decode($data);
    }
    @unlink($plain);
    @unlink($signed);
    throw new EWPException('Could not sign message');
  }

  public function encrypt($message, $recipientCertificate)
  {
    $signed = $this->newTempFile();
    $encrypted = $this->newTempFile();

    $ptr = fopen($signed, 'wb');
    fwrite($ptr, $message);
    fclose($ptr);

    if (openssl_pkcs7_encrypt($signed, $encrypted, $recipientCertificate, array(), PKCS7_BINARY, OPENSSL_CIPHER_3DES)) {
      $data = $this->stripHeader(file_get_contents($encrypted));
      @unlink($signed);
      @unlink($encrypted);
      return $this->addPEMDelimiters($data);
    }
    @unlink($signed);
    @unlink($encrypted);
    throw new EWPException('Could not encrypt message');
  }

  protected function stripHeader($string)
  {
    $exploded = explode("\n\n", $string);
    return $exploded[1];
  }

  protected function addPEMDelimiters($string)
  {
    return "-----BEGIN PKCS7-----" . str_replace("\n", "", $string) . "-----END PKCS7-----";
  }

  protected function newTempFile($prefix = 'simplepaypal_')
  {
    return tempnam($this->tempFileDirectory, $prefix);
  }

  protected function checkPrivateKey($certificate, $key)
  {
    $certificate = openssl_x509_read($certificate);
    $key = $this->processKey($key);
    if ( openssl_x509_check_private_key($certificate, $key) ) {
      return $key;
    }
    throw new EWPException('Invalid certificate/privateKey pair.');
  }

  protected function processKey($key)
  {
    return is_array($key) ? openssl_get_privatekey($key[0], $key[1]) : openssl_get_privatekey($key);
  }
}
