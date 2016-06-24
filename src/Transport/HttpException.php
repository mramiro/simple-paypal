<?php namespace SimplePaypal\Transport;

class HttpException extends \RuntimeException
{
  public function setMessage($message)
  {
    $this->message = $message;
  }
}
