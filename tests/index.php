<?php

include __DIR__.'/bootstrap.php';

use SimplePaypal\Common\Item;

$manager = newManagerForDebug();

$cart = $manager->createUploadCartButton();
$cart->addItem(new Item('A blue shirt', 14.99));
$cart->addItem(new Item('Dog food', 3.00, 3));

?>
<html>
  <head>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.css" media="screen" title="no title" charset="utf-8"> -->
    <link rel="stylesheet" href="sp-buttons.css" media="screen" charset="utf-8">
  </head>
  <body>
    <h1>Plaintext</h1>
    <?php
    echo $cart->render('small');
    echo $cart->render('medium');
    echo $cart->render('large');
    ?>
    <h1>Encrypted</h1>
    <?php
    $encrypted = $manager->encryptButton($cart);
    $encrypted->setStyle('yellow');
    echo $encrypted->render('small');
    echo $encrypted->render('medium');
    echo $encrypted->render('large');
    ?>
  </body>
</html>
