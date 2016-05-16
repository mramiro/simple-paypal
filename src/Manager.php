<?php namespace SimplePaypal;

use RuntimeException;
use SimplePaypal\Support\Configurable;
use SimplePaypal\Common\Constants;
use SimplePaypal\Common\Types\Currency;
use SimplePaypal\Transport\HttpClientInterface;
use SimplePaypal\Transport\CurlHandler;

class Manager extends Configurable
{
  protected $pdtToken;
  protected $debug;
  protected $httpClient;
  protected $currency;
  protected $businessId;

  protected function getConfigOptions()
  {
    return array(
      'debug' => false,
      'currency' => Constants::DEFAULT_CURRENCY,
      'http_client' => function() { return new CurlHandler(); },
      'pdt_token' => null,
      'business_id' => null
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

  public function validatePdtTransaction($transactionId)
  {
    $client = $this->getHttpClient();
    $response = $client->post($this->getEndpoint(), array(
      'cmd' => '_notify-synch',
      'tx' => $transactionId,
      'at' => $this->getPdtToken()
    ));
    if ($transaction = PDT\Transaction::fromResponseText($response)) {
      return $transaction;
    }
    else {
      throw new RuntimeException(
        'Could not obtain transaction information from Paypal. Transaction id = ' . $transactionId
      );
    }
  }

  public function createUploadCartButton()
  {
    $cart = new Html\Carts\UploadCart(array(
      'currency_code' => $this->getCurrency(),
      'business' => $this->getBusinessId()
    ));
    $cart->setFormAction($this->getEndpoint());
    return $cart;
  }

}
