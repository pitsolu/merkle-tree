<?php

require "bootstrap.php";

use Merkle\{Tree, Leaf};

$customer = sha1("John Smith");
$retailer = sha1("John Doe");
$merchant = sha1("Amazon");
$taxman = sha1("KRA");
$courier = sha1("Shiply");
$exchange = sha1("Coinbase");

// Tree::hashFunc(function($data){

// 	return hash("sha256", hash("sha256", $data));
// });

Tree::hashFunc(function($data){

	return sha1($data);
});


// echo Merkle\Tree::hash("samweru");

$transactions = array("purchase"=>new Leaf(array(

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
)));

$treeA = new Tree();
$treeB = new Tree();

foreach($transactions as $name=>$trx){

	$treeA->add($trx);

	if($name != "trx-fees")
		$treeB->add($trx);
}

$treeA = $treeA->getTree();
$treeB = $treeB->getTree();

// print_r($treeA);
// print_r($treeB);

while(is_array($treeA)){

	$treeA = reset($treeA);
	$treeB = reset($treeB);

	print_r(array(array_keys($treeA),array_keys($treeB)));

	// echo "222--";
	// print_r(array_keys($treeA));

	// echo "111--";
	// print_r(array_keys($treeB));
}