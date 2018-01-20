<?php

use Merkle\{Tree, Leaf};

class MerkleTest extends PHPUnit_Framework_TestCase{

	public function setUp(){

		$this->customer = sha1("John Smith");
		$this->retailer = sha1("John Doe");
		$this->merchant = sha1("Amazon");
		$this->taxman = sha1("KRA");
		$this->courier = sha1("Shiply");
		$this->exchange = sha1("Coinbase");

		Tree::hashFunc(function($data){

			return hash("sha256", hash("sha256", $data));
		});
	}

	public function testHashFunctionSetUp(){

		$hash = hash("sha256", hash("sha256", "p@55w0rd"));

		$this->assertEquals($hash, Tree::doHash("p@55w0rd"));
	}

	public function testNoDuplicates(){

		$tree = new Tree();
		$tree->add(new Leaf(array(

			"sender"=>$this->customer,
			"recipient"=>$this->retailer,
			"amount"=>5
		)));
		$tree->add(new Leaf(array(

			"sender"=>$this->customer,
			"recipient"=>$this->retailer,
			"amount"=>5
		)));

		while(is_array($tree))
			$tree = reset($tree);

		$tree = $tree->getTree();

		$this->assertTrue(count($tree) == 1);
	}

	public function testHashingLimit(){

		$transactions = array(

			"purchase"=>array(

				"sender"=>$this->customer,
				"recipient"=>$this->retailer,
				"amount"=>100
			),
			"w/h-tax"=>array(

				"sender"=>$this->customer,
				"recipient"=>$this->taxman,
				"amount"=>10
			),
			"commission"=>array(

				"sender"=>$this->customer,
				"recipient"=>$this->merchant,
				"amount"=>5
			),
			"freight"=>array(

				"sender"=>$this->customer,
				"recipient"=>$this->courier,
				"amount"=>5
			),
			"trx-fees"=>array(

				"sender"=>$this->customer,
				"recipient"=>$this->exchange,
				"amount"=>1
			)
		);

		$tree = new Tree();
		foreach($transactions as $name=>$trx)
			$tree->add(new Leaf($trx));

		$branches[] = $tree->hash();
		while(true){

			$prev = end($branches);
			$next = $tree->hash();
			$branches[] = $next;
			
			if($prev == $next){//hashing limit attained

				$this->assertTrue(true);
				break;
			}
		}
	}
}