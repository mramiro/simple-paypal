<?php namespace SimplePaypal;

use RuntimeException;
use SimplePaypal\Support\Constants;
use SimplePaypal\Transport\HttpClientInterface;

class Manager
{
  protected $pdtToken;
  protected $debug;
  protected $httpClient;

  public function __construct(array $options = array())
  {
    $this->parseOptions($options);
  }

  protected function parseOptions(array $opts)
  {
    if (isset($opts['debug'])) $this->debug = $opts['debug'];
    if (isset($opts['pdt_token'])) $this->pdtToken = $opts['pdt_token'];
  }

  public function getEndpoint()
  {
    return $this->debug ? Constants::SANDBOX_ENDPOINT : Constants::ENDPOINT;
  }

  public function setHttpClient(HttpClientInterface $client)
  {
    $this->httpClient = $client;
  }

  protected function getHttpClient()
  {
    return $this->httpClient;
  }

  protected function getPdtToken()
  {
    return $this->$pdtToken;
  }

  public function validatePdtTransaction($transactionId)
  {
    $client = $this->getHttpClient();
    $response = $client->post($this->getEndpoint(), array(
      'cmd' => '_notify-synch',
      'tx' => $transactionId,
      'at' => $this->getPdtToken()
    ));
    if ($transaction = Transaction::fromPdtResponse($response)) {
      return $transaction;
    }
    else {
      throw new RuntimeException(
        'Could not obtain transaction information from Paypal. Transaction id = ' . $transactionId
      );
    }
  }

}
