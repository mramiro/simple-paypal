<?php namespace SimplePaypal\Http;

class HttpException extends \RuntimeException
{
  public function setMessage($message)
  {
    $this->message = $message;
  }
}
