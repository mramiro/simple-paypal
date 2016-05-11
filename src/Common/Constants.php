<?php namespace SimplePaypal\Common;

class Constants
{
  const ENDPOINT = 'https://www.paypal.com/cgi-bin/webscr';
  const SANDBOX_ENDPOINT = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

  protected static $currencies = array(
    'AUD' => 'Australian Dollar',
    'BRL' => 'Brazilian Real',
    'CAD' => 'Canadian Dollar',
    'CZK' => 'Czech Koruna',
    'DKK' => 'Danish Krone',
    'EUR' => 'Euro',
    'HKD' => 'Hong Kong Dollar',
    'HUF' => 'Hungarian Forint',
    'ILS' => 'Israeli New Sheqel',
    'JPY' => 'Japanese Yen',
    'MYR' => 'Malaysian Ringgit',
    'MXN' => 'Mexican Peso',
    'NOK' => 'Norwegian Krone',
    'NZD' => 'New Zealand Dollar',
    'PHP' => 'Philippine Peso',
    'PLN' => 'Polish Zloty',
    'GBP' => 'Pound Sterling',
    'RUB' => 'Russian Ruble',
    'SGD' => 'Singapore Dollar',
    'SEK' => 'Swedish Krona',
    'CHF' => 'Swiss Franc',
    'TWD' => 'Taiwan New Dollar',
    'THB' => 'Thai Baht',
    'TRY' => 'Turkish Lira',
    'USD' => 'U.S. Dollar'
  );

  const DEFAULT_CURRENCY = 'USD';

  private static function normalizeCurrency($currency)
  {
    return strtoupper($currency);
  }

  public static function currencyIsValid($currency)
  {
    return in_array(static::normalizeCurrency($currency), static::$currencies);
  }

  public static function getCurrencies()
  {
    return static::$currencies;
  }

  public static function getCurrencyName($currency)
  {
    return static::currencyIsValid($currency) ? static::$currencies[$currency] : null;
  }
}
