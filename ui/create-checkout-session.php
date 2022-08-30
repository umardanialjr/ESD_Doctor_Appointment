<?php

include '../stripe-php-master/init.php';
\Stripe\Stripe::setApiKey('sk_test_51IXmFGAB2qhMc6CzNkZqLOFpfjVTuyenmW0V67nbedIIXMcBQIuAl2B8GnnmSxIuMDgOx3JS9S2tEOTC1jk8GS5500YaL9ssyi');

header('Content-Type: application/json');

//$YOUR_DOMAIN = 'http://localhost/GitHub/ESD_Doctor_Patient_appointment/';
$YOUR_DOMAIN = 'http://localhost';

$checkout_session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'price_data' => [
      'currency' => 'sgd',
      'unit_amount' => 4000,
      'product_data' => [
        'name' => 'Your Appointment Costs',
      ],
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/ui/success.php',
  'cancel_url' => $YOUR_DOMAIN . '/ui/profile.php',
]);

echo json_encode(['id' => $checkout_session->id]);

?>