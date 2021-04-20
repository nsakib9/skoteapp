<?php

$payment_methods = array(
	["key" => "stripe", "value" => 'Card Payment', 'icon' => asset("images/icon/card.png")],
);

if(!defined('PAYMENT_METHODS')) {
	define('PAYMENT_METHODS', $payment_methods);
}

$payout_methods = array(
	["key" => "stripe", "value" => 'Stripe'],
);

if(!defined('PAYOUT_METHODS')) {
	define('PAYOUT_METHODS', $payout_methods);
}

if(!defined('CACHE_HOURS')) {
	define('CACHE_HOURS', 24);
}
