<?php namespace SimplePaypal;

use RuntimeException;
use SimplePaypal\Support\Configurable;
use SimplePaypal\Common\Constants;
use SimplePaypal\Common\TransactionValidator;
use SimplePaypal\Http\HttpClientInterface;
use SimplePaypal\Buttons\Button;

class Manager extends Configurable
{
  protected $pdtToken;
  protected $httpClient;
  protected $currency = Constants::DEFAULT_CURRENCY;
  protected $businessId;
  protected $forcedLocale;
  protected $country;
  protected $vendor;
  protected $ewpCert, $ewpCertId, $ewpKey, $ewpKeyPass, $ewpPaypalCert;

  protected $ewpEncryptor;
  protected $endpoint = Constants::ENDPOINT;

  public function __construct(array $config = array(), $sandbox = false)
  {
    if ($sandbox) {
      $this->endpoint = str_replace('paypal', 'sandbox.paypal', $this->endpoint);
    }
    parent::__construct($config);
  }

  protected function defaults()
  {
    return array(
      'http_client' => function() {
        if (Http\CurlHandler::checkCompatibility()) {
          return new Http\CurlHandler();
        }
        if (Http\NodejsHandler::checkCompatibility()) {
          return new Http\NodejsHandler();
        }
        return new Http\CurlHandler();
      }
    );
  }

  public function setHttpClient(HttpClientInterface $client)
  {
    $this->httpClient = $client;
  }

  public function validateTransactionPdt($transaction = null)
  {
    return $this->validateTransaction(new PDT\Validator(
      $this->getPdtToken(),
      $this->getHttpClient(),
      $this->endpoint
    ), $transaction);
  }

  public function validateTransactionIpn($transaction = null)
  {
    return $this->validateTransaction(new IPN\Validator(
      $this->getHttpClient(),
      $this->endpoint
    ), $transaction);
  }

  protected function validateTransaction(TransactionValidator $validator, $transaction = null)
  {
    if (is_null($transaction)) {
      $transaction = $validator->transactionFromGlobals();
    }
    return $transaction->validate($validator);
  }

  public function createUploadCartButton(array $items = array())
  {
    $cart = $this->decorateButton(new Buttons\Carts\UploadCart());
    $cart->setItems($items);
    return $cart;
  }

  protected function decorateButton(Button $btn)
  {
    Button::conform();
    $btn->setFormAction($this->endpoint);
    $btn->business = $this->getBusinessId();
    $btn->currency_code = $this->getCurrency();
    if (isset($this->forcedLocale)) {
      $btn->lc = $this->forcedLocale;
    }
    if (isset($this->country) && isset($this->vendor)) {
      $btn->setBuildNotation($this->vendor, $this->country);
    }
    Button::complain();
    return $btn;
  }

  protected function getEncryptor()
  {
    if (!isset($this->ewpEncryptor)) {
      $key = isset($this->ewpKeyPass) ? array($this->ewpKey, $this->ewpKeyPass) : $this->ewpKey;
      $this->ewpEncryptor = new EWP\Encryptor($this->ewpCert, $key, $this->ewpPaypalCert);
    }
    return $this->ewpEncryptor;
  }

  public function encryptButton(Button $btn)
  {
    $btn = $this->decorateButton(new EWP\EncryptedButton($this->ewpCertId, $btn));
    return $btn->encrypt($this->getEncryptor());
  }

}
