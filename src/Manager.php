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
  protected $debug;
  protected $httpClient;
  protected $currency;
  protected $businessId;
  protected $forcedLocale;
  protected $country;
  protected $vendor;
  protected $ewpCert, $ewpCertId, $ewpKey, $ewpKeyPass, $ewpPaypalCert;

  protected $ewpEncryptor;

  protected function getConfigOptions()
  {
    return array(
      'debug' => false,
      'currency' => Constants::DEFAULT_CURRENCY,
      'http_client' => function() { return new CurlHandler(); },
      'pdt_token' => null,
      'business_id' => null,
      'forced_locale' => null,
      'country' => null,
      'vendor' => null,
      'ewp_cert' => null,
      'ewp_cert_id' => null,
      'ewp_key' => null,
      'ewp_key_pass' => null,
      'ewp_paypal_cert' => null
    );
  }

  public function getEndpoint()
  {
    return $this->debug ? Constants::SANDBOX_ENDPOINT : Constants::ENDPOINT;
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
    $response = $client->post($this->getEndpoint(), array(
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
    $btn->setFormAction($this->getEndpoint());
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
