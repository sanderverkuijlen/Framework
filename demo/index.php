<?php
$includePath = '../';

require_once($includePath.'lib/head.php');


$phone = new Product('iPhone 4s',       'Apple inc.', 599.99);
$phone->name = 'iPhone 4';
$phone->save();
$phone->delete();

$phone = new Product('iPhone 5',        'Apple inc.', 599.99);
$phone->save();

$cover = new Product('iPhone 5 cover',  'Apple inc.', 59.99);
$cover->save();


$customer = new Customer('John Doe', 'johndoe@mail.com', '1234');
$customer->save();

$order = new Order($customer->id, date('d-m-Y'));
$order->save();

$order->addProduct($phone);
$order->addProduct($cover);

$order->removeProduct($cover);


$customer = Customer::get(1);
$customer->name = 'Jane Doe';
$customer->save();
