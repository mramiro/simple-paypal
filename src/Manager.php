<?php namespace SimplePaypal;

use RuntimeException;
use SimplePaypal\Support\Configurable;
use SimplePaypal\Common\Constants;
use SimplePaypal\Common\Types\Currency;
use SimplePaypal\Common\Types\Country;
use SimplePaypal\Common\Types\Locale;
use SimplePaypal\Transport\HttpClientInterface;
use SimplePaypal\Transport\CurlHandler;
use SimplePaypal\Html\Button;

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
      'http_client' => function() { return new CurlHandler(); }
    );
  }

  public function setHttpClient(HttpClientInterface $client)
  {
    $this->httpClient = $client;
  }

  public function setCurrency($currency)
  {
    $this->currency = $currency instanceof Currency ? $currency : new Currency($currency);
  }

  public function setForcedLocale($locale)
  {
    $this->forcedLocale = $locale instanceof Locale ? $locale : new Locale($locale);
  }

  public function setCountry($country)
  {
    $this->country = $country instanceof Country ? $country : new Country($country);
  }

  public function validatePdtTransaction($transactionId)
  {
    $client = $this->getHttpClient();
    $response = $client->post($this->endpoint, array(
      'cmd' => '_notify-synch',
      'tx' => $transactionId,
      'at' => $this->getPdtToken()
    ));
    if ($transaction = PDT\Transaction::fromString($response)) {
      return $transaction;
    }
    else {
      throw new RuntimeException(
        'Could not obtain transaction information from Paypal. Transaction id = ' . $transactionId
      );
    }
  }

  public function createUploadCartButton(array $items = array())
  {
    $cart = $this->decorateButton(new Html\Carts\UploadCart());
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
