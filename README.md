Merkle Tree
===========

## Usage

```php

require("vendor/autoload.php");

$customer = sha1("John Smith");
$retailer = sha1("John Doe");
$merchant = sha1("Amazon");
$taxman = sha1("Revenue Authority");
$courier = sha1("Shiply");
$exchange = sha1("Coinbase");

Merkle\Tree::hashFunc(function($data){

    return hash("sha256", hash("sha256", $data));
});

$transactions = array(
"purchase"=>new Merkle\Leaf(array(

    "sender"=>$customer,
    "recipient"=>$retailer,
    "amount"=>100
)),
"w/h-tax"=>new Merkle\Leaf(array(

    "sender"=>$customer,
    "recipient"=>$taxman,
    "amount"=>10
)),
"commission"=>new Merkle\Leaf(array(

    "sender"=>$customer,
    "recipient"=>$merchant,
    "amount"=>5
)),
"freight"=>new Merkle\Leaf(array(

    "sender"=>$customer,
    "recipient"=>$courier,
    "amount"=>5
)),
"trx-fees"=>new Merkle\Leaf(array(

    "sender"=>$customer,
    "recipient"=>$exchange,
    "amount"=>1
)));

$merkleTree = new Merkle\Tree();
foreach($transactions as $name=>$trx)
    $merkleTree->add($trx);

$tree = $merkleTree->getTree();

// print_r($tree);

echo(key($tree));

```