<?php namespace SimplePaypal\Common;

class Constants
{
  const ENDPOINT = 'https://www.paypal.com/cgi-bin/webscr';
  const SANDBOX_ENDPOINT = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

  const DEFAULT_CURRENCY = Types\Currency::US_DOLLAR;
  const DEFAULT_LOCALE = Types\Locale::UNITED_STATES;
}
