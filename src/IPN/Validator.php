<?php namespace SimplePaypal\IPN;

use UnexpectedValueException;
use SimplePaypal\Http\HttpClientInterface;
use SimplePaypal\Http\HttpException;
use SimplePaypal\Common\Transaction;
use SimplePaypal\Common\TransactionValidator;

class Validator implements TransactionValidator
{
  const MSG_OK = 'VERIFIED';
  const MSG_NOT_OK = 'INVALID';

  protected $client;
  protected $endpoint;

  public function __construct(HttpClientInterface $client, $endpoint)
  {
    $this->client = $client;
    $this->endpoint = $endpoint;
  }

  public function transactionFromGlobals()
  {
    return new Transaction(static::parseRawPostData());
  }

  protected static function parseRawPostData()
  {
    $data = array();
    $raw = file_get_contents('php://input');
    $items = preg_split('/&/', $raw, null, PREG_SPLIT_NO_EMPTY);
    foreach ($items as $item) {
      list($k, $v) = explode('=', $item);
      $data[$k] = urldecode($v);
    }
    return $data;
  }

  public function validate(Transaction $transaction)
  {
    $postData = array_merge(array('cmd' => '_notify-validate'), $transaction->toArray());
    $postData = http_build_query($postData);
    try {
      $response = trim($this->client->post($this->endpoint, $postData));
      if ($response == static::MSG_OK) {
        return true;
      }
      elseif ($response == static::MSG_NOT_OK) {
        return false;
      }
      throw new UnexpectedValueException(
        'IPN: Paypal responded with an unexpected verification status: ' . $response
      );
    }
    catch (HttpException $e) {
      $e->setMessage('Could not communicate with Paypal');
      throw $e;
    }
  }

}
