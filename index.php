<?php

require "bootstrap.php";

use Merkle\{Tree, Leaf};

$customer = sha1("John Smith");
$retailer = sha1("John Doe");
$merchant = sha1("Amazon");
$taxman = sha1("Tax Revenue");
$courier = sha1("Shiply");
$exchange = sha1("Coinbase");

$transactions = array(

	"purchase"=>new Leaf(array(

		"sender"=>$customer,
		"recipient"=>$retailer,
		"amount"=>100
	)),
	"w/h-tax"=>new Leaf(array(

		"sender"=>$customer,
		"recipient"=>$taxman,
		"amount"=>10
	)),
	"commission"=>new Leaf(array(

		"sender"=>$customer,
		"recipient"=>$merchant,
		"amount"=>5
	)),
	"freight"=>new Leaf(array(

		"sender"=>$customer,
		"recipient"=>$courier,
		"amount"=>5
	)),
	"trx-fees"=>new Leaf(array(

		"sender"=>$customer,
		"recipient"=>$exchange,
		"amount"=>1
	))
);

$hash = function($data){

	return sha1($data);
};

$treeA = new Tree($hash);
$treeB = new Tree($hash);

foreach($transactions as $name=>$trx){

	$nodeA = $treeA->add($trx);

	// Make inconsistent trx
	if($name != "freight")
		$nodeB = $treeB->add($trx);

	if($nodeA != $nodeB){

		// Print inconsistent trx
		print_r($trx);
		break;
	}
}