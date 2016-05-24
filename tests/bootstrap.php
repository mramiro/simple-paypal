<?php

require __DIR__.'/../vendor/autoload.php';

function newManagerForDebug()
{
  return new SimplePaypal\Manager(array(
    'debug' => true,
    'pdt_token' => getenv('PDT_TOKEN'),
    'business_id' => getenv('BUSINESS_ID'),
    'forced_locale' => getenv('LOCALE'),
    'country' => getenv('COUNTRY'),
    'vendor' => getenv('VENDOR'),
    'ewp_cert' => realpath(getenv('EWP_CERT_FILE')),
    'ewp_key' => realpath(getenv('EWP_KEY_FILE')),
    'ewp_paypal_cert' => realpath(getenv('EWP_PAYPAL_CERT_FILE')),
    'ewp_cert_id' => getenv('EWP_CERT_ID')
  ));
}

$de = new Dotenv\Dotenv(__DIR__);
$de->load();
