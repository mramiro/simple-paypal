<?php namespace SimplePaypal\Common;

interface TransactionValidator
{
  public function validate(Transaction $transaction);
  public function transactionFromGlobals();
}
