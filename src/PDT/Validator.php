<?php namespace SimplePaypal\PDT;

use SimplePaypal\Transport\HttpClientInterface;
use SimplePaypal\Transport\HttpException;

class Validator
{
  protected $client;
  protected $token;
  protected $endpoint;

  public function __construct($token, HttpClientInterface $client, $endpoint)
  {
    $this->token = $token;
    $this->client = $client;
    $this->endpoint = $endpoint;
  }

  public function validate($transactionId)
  {
    return Transaction::fromString($this->getResponse($transactionId));
  }

  protected function getResponse($transactionId)
  {
    try {
      $post = array(
        'cmd' => '_notify-synch',
        'tx' => $transactionId,
        'at' => $this->token
      );
      return $this->client->post($this->endpoint, $post);
    }
    catch (HttpException $e) {
      $e->setMessage('Could not communicate with Paypal');
      throw $e;
    }
  }
}
