<?php namespace SimplePaypal\IPN;

use SimplePaypal\Http\HttpClientInterface;
use SimplePaypal\Http\HttpException;
use SimplePaypal\Common\Transaction;
use SimplePaypal\Common\TransactionValidator;

class Validator implements TransactionValidator
{
  protected $client;
  protected $endpoint;

  public function __construct(HttpClientInterface $client, $endpoint)
  {
    $this->client = $client;
    $this->endpoint = $endpoint;
  }

  public function validate(Transaction $transaction)
  {
    $postData = array('cmd' => 'notify-validate') + $transaction->toArray();
    $postData = http_build_query($postData);
    try {
      $response = $this->client->post($this->endpoint, $postData);
      return trim($response) == 'VERIFIED';
    }
    catch (HttpException $e) {
      $e->setMessage('Could not communicate with Paypal');
      throw $e;
    }
  }

}
