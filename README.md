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

$transactions = array(

    "purchase"=>array(

        "sender"=>$customer,
        "recipient"=>$retailer,
        "amount"=>100
    ),
    "w/h-tax"=>array(

        "sender"=>$customer,
        "recipient"=>$taxman,
        "amount"=>10
    ),
    "commission"=>array(

        "sender"=>$customer,
        "recipient"=>$merchant,
        "amount"=>5
    ),
    "freight"=>array(

        "sender"=>$customer,
        "recipient"=>$courier,
        "amount"=>5
    ),
    "trx-fees"=>array(

        "sender"=>$customer,
        "recipient"=>$exchange,
        "amount"=>1
    )
);

Merkle\Tree::hashFunc(function($data){

    return hash("sha256", hash("sha256", $data));
});

$merkleTree = new Merkle\Tree();
foreach($transactions as $name=>$trx)
    $merkleTree->add(new Merkle\Leaf($trx));

$tree = $merkleTree->getTree();

// print_r($tree); //merkle tree

echo(key($tree)); //merle root

```