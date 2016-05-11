<?php namespace SimplePaypal;

use RuntimeException;
use SimplePaypal\Support\Configurable;
use SimplePaypal\Common\Constants;
use SimplePaypal\Common\ConfigResolver;
use SimplePaypal\Transport\HttpClientInterface;
use SimplePaypal\Transport\CurlHandler;

class Manager extends Configurable implements ConfigResolver
{
  protected static $options = array(
    'debug' => false,
    'pdt_token' => null,
    'currency' => Constants::DEFAULT_CURRENCY,
    'http_client' => 'curl',
    'business_id' => null
  );

  protected $pdtToken;
  protected $debug;
  protected $httpClient;
  protected $currency;
  protected $businessId;

  public function getEndpoint()
  {
    return $this->debug ? Constants::SANDBOX_ENDPOINT : Constants::ENDPOINT;
  }

  public function setHttpClient($client)
  {
    if ($client instanceof HttpClientInterface) {
      $this->httpClient = $client;
    }
    if ($client == 'curl') {
      $this->httpClient = new CurlHandler();
    }
  }

  public function getHttpClient()
  {
    return $this->httpClient;
  }

  public function getPdtToken()
  {
    return $this->pdtToken;
  }

  public function setPdtToken($token)
  {
    $this->pdtToken = $token;
  }

  public function getCurrency()
  {
    return $this->currency;
  }

  public function setCurrency($currency)
  {
    if (Constants::currencyIsValid($currency)) {
      $this->currency = $currency;
    }
  }

  public function getBusinessId()
  {
    return $this->businessId();
  }

  public function setBusinessId($id)
  {
    $this->businessId($id);
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

  public function createCartUploadButton()
  {
    return new CartUpload($this);
  }

}
