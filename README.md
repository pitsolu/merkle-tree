Merkle Tree
===========

## Usage

### Leaf (Array)

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
        "amount"=>110
    ),
    "w/h-tax"=>array(

        "sender"=>$retailer,
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

$merkleTree = new Merkle\Tree(function($data){

    return hash("sha256", hash("sha256", $data));
});
// foreach($transactions as $name=>$trx)
//     $tree = $merkleTree->add(new Merkle\Leaf($trx));

$tree = $merkleTree->add(new Merkle\Leaf($transactions["purchase"]));
$tree = $merkleTree->add(new Merkle\Leaf($transactions["w/h-tax"]));
$tree = $merkleTree->add(new Merkle\Leaf($transactions["commission"]));
$tree = $merkleTree->add(new Merkle\Leaf($transactions["freight"]));
$tree = $merkleTree->add(new Merkle\Leaf($transactions["trx-fees"]));

// print_r($tree); //merkle tree

echo(key($tree)); //merle root

```

### Leaf Item (String)

The example below also tests consistency between two trees.

```php
$slimLyrics = $realSlimShadyLyrics = array(

    "I'm Slim Shady, yes I'm the real Shady",
    "All you other Slim Shadys are just imitating",
    "So won't the real Slim Shady please stand up",
    "Please stand up, please stand up?"
);

$errIdx = 2;

$slimLyrics[$errIdx] = sprintf("%s ???", $slimLyrics[$errIdx]);

foreach($realSlimShadyLyrics as $idx=>$lyric){

    $tree = $this->merkleTree->add(new Merkle\LeafItem($lyric));
    $xtree = $this->merkleTree->add(new Merkle\LeafItem($slimLyrics[$idx]));   

    if(key($tree)!=key($xtree))
        throw new \Exception("Slims lyrics don't match!");        
}
```