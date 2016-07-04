<?php namespace SimplePaypal\Common;

interface TransactionValidator
{
  public function validate(Transaction $transaction);
}
