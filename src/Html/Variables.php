<?php namespace SimplePaypal\Html;

class Variables
{
  public static $base = array(
    'cmd',
    'notify_url',
    'bn'
  );

  public static $items = array(
    'amount',
    'discount_amount',
    'discount_amount2',
    'discount_rate',
    'discount_rate2',
    'discount_num',
    'item_name',
    'item_number',
    'quantity',
    'shipping',
    'shipping2',
    'tax',
    'tax_rate',
    'undefined_quantity',
    'weight',
    'weight_unit',
    'on0',
    'on1',
    'os0',
    'os1',
    'option_index',
    'option_select0',
    'option_amount0',
    'option_select1',
    'option_amount1'
  );

  public static $paymentTransactions = array(
    'address_override',
    'currency_code',
    'custom',
    'handling',
    'invoice',
    'tax_cart',
    'weight_cart',
    'weight_unit'
  );

  public static $carts = array(
    'address_override',
    'currency_code',
    'custom',
    'handling',
    'invoice',
    'tax_cart',
    'weight_cart',
    'weight_unit'
  );

  public static $uploadedCarts = array(
    'upload'
  );

  public static $hostedCarts = array(
    'add',
    'display'
  );

  public static $checkoutPages = array(
    'page_style',
    'image_url',
    'cpp_cart_border_color',
    'cpp_header_image',
    'cpp_headerback_color',
    'cpp_hedear_border_color',
    'cpp_logo_image',
    'cpp_payflow_color',
    'lc',
    'no_note',
    'cn',
    'no_shipping',
    'return',
    'rm',
    'cbt',
    'cancel_return'
  );

  public static $addressOverrides = array(
    'address1',
    'address2',
    'city',
    'country',
    'email',
    'first_name',
    'last_name',
    'lc',
    'charset',
    'night_phone_a',
    'night_phone_b',
    'night_phone_c',
    'state',
    'zip'
  );
}
