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
    'vendor' => getenv('VENDOR')
  ));
}

$de = new Dotenv\Dotenv(__DIR__);
$de->load();
