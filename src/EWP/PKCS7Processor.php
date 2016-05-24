<?php namespace SimplePaypal\EWP;

interface PKCS7Processor
{
  /**
   * Sign a message using the provided certificate and private key
   *
   * @param string $message The message to be signed
   * @param string $certificate A PEM encoded certificate used to sign the message
   * @param string|array $privateKey Either a string containing a PEM encoded RSA private key used
   *  to sign the message or an array in the form: [ 0 => string $KEY, 1 => string $KEY_PASSPHRASE ]
   * @return string The binary representation of the signed message
   */
  public function sign($message, $certificate, $privateKey);

  /**
   * Encrypt a binary message using the intended recipient public certificate
   *
   * @param string $message The message to be encrypted
   * @param string $recipientCertificate A PEM encoded certificate
   * @return string The PEM encoded representation of the encrypted message
   */
  public function encrypt($message, $recipientCertificate);
}
