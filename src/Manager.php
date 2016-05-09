<?php namespace SimplePaypal;

use RuntimeException;
use SimplePaypal\Support\Constants;
use SimplePaypal\Transport\HttpClientInterface;
use SimplePaypal\Transport\CurlHandler;

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
    if (isset($opts['http_client']) && $opts['http_client'] instanceof HttpClientInterface) {
      $this->setHttpClient($opts['http_client']);
    }
    else {
      $this->setHttpClient(new CurlHandler());
    }
  }

  public function getEndpoint()
  {
    return $this->debug ? Constants::SANDBOX_ENDPOINT : Constants::ENDPOINT;
  }

  public function setHttpClient(HttpClientInterface $client)
  {
    $this->httpClient = $client;
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

}
