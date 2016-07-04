<?php namespace SimplePaypal\PDT;

use SimplePaypal\Http\HttpClientInterface;
use SimplePaypal\Http\HttpException;
use SimplePaypal\Common\Transaction;
use SimplePaypal\Common\TransactionValidator;

class Validator implements TransactionValidator
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

  public function transactionFromGlobals()
  {
    return new Transaction($_GET['tx']);
  }

  public function validate(Transaction $transaction)
  {
    $response = $this->getResponse($transaction->txn_id);
    $data = Parser::parse($response);
    if (is_array($data)) {
      $transaction->fill($data);
      return true;
    }
    $transaction->error = $data;
    return false;
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
