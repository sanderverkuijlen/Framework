<?php
$path = '../';

require_once($path.'lib/head.php');

$user = User::get(1);

echo printR($user);

$user->email    = time();
$user->password = time();

$user->save();
?>