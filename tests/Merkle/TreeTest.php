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

		$this->merkleTree = new Tree(function($data){

			return hash("sha256", hash("sha256", $data));
		});
	}

	public function testHashFunctionSetUp(){

		$hash = hash("sha256", hash("sha256", "p@55w0rd"));

		$this->assertEquals($hash, $this->merkleTree->doHash("p@55w0rd"));
	}

	public function testNoDuplicatesAtLeafInsertion(){

		$this->merkleTree->add(new Leaf(array(

			"sender"=>$this->customer,
			"recipient"=>$this->retailer,
			"amount"=>5
		)));

		$tree = $this->merkleTree->add(new Leaf(array(

			"sender"=>$this->customer,
			"recipient"=>$this->retailer,
			"amount"=>5
		)));

		$this->assertTrue(count($tree) == 1);
	}

	public function testReuseHashOnLastUnevenTransaction(){

		$transactions = array(

			array(

				"sender"=>$this->customer,
				"recipient"=>$this->retailer,
				"amount"=>100
			),
			array(

				"sender"=>$this->customer,
				"recipient"=>$this->taxman,
				"amount"=>10
			),
			array(

				"sender"=>$this->customer,
				"recipient"=>$this->merchant,
				"amount"=>5
			)
		);

		foreach($transactions as $trx)
			$tree = $this->merkleTree->add($leaf = new Leaf($trx));

		$tree = end($tree);
		end($tree);
		$lastTreeNodeHash = key($tree);

		$lastLeafHash = $this->merkleTree->doHash((string)$leaf);

		$this->assertTrue($lastTreeNodeHash == $this->merkleTree->doHash(str_repeat($lastLeafHash, 2)));
	}
}