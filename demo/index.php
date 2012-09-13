<?php
$path = '../';

require_once($path.'lib/head.php');


$phone = new Product('iPhone 5',        'Apple inc.', 599.99);
$phone->save();

$cover = new Product('iPhone 5 cover',  'Apple inc.', 59.99);
$cover->save();


$customer = new Customer('John Doe', 'johndoe@mail.com', '1234');
$customer->save();

$order = new Order($customer->id, date('d-m-Y'));
$order->save();

//TODO: goeie manier bedenken om objecten te (ont-)koppelen in NN relaties



$customer   = Customer::get($customer->id);
$orders     = $customer->orders();

echo printR($customer);
echo printR($orders);

//TODO: goeie manier bedenken om objecten op te halen op basis van NN relaties
?>