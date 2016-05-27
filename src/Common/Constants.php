<?php namespace SimplePaypal\Common;

class Constants
{
  const ENDPOINT = 'https://www.paypal.com/cgi-bin/webscr';
  const DEFAULT_CURRENCY = Types\Currency::US_DOLLAR;
  const DEFAULT_LOCALE = Types\Locale::UNITED_STATES;
  const DEFAULT_COUNTRY = Types\Country::UNITED_STATES;
}
