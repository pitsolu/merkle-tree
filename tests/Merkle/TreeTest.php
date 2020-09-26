<?php

use Merkle\{Tree, Leaf, LeafItem};

class MerkleTest extends PHPUnit\Framework\TestCase{

	public function setUp():void{

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

	public function testLeafItemsAndAlsoFindInconsistency(){

		$slimLyrics = $realSlimShadyLyrics = array(

			"I'm Slim Shady, yes I'm the real Shady",
			"All you other Slim Shadys are just imitating",
			"So won't the real Slim Shady please stand up",
			"Please stand up, please stand up?"
		);

		$errIdx = 2;

		$slimLyrics[$errIdx] = sprintf("%s ???", $slimLyrics[$errIdx]);

		foreach($realSlimShadyLyrics as $idx=>$lyric){

			$tree = $this->merkleTree->add(new LeafItem($lyric));
			$xtree = $this->merkleTree->add(new LeafItem($slimLyrics[$idx]));	

			if(key($tree)!=key($xtree))
				$this->assertEquals($idx, $errIdx); 		
		}
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

		$this->assertEquals($lastTreeNodeHash, 
							$this->merkleTree->doHash(str_repeat($lastLeafHash, 2)));
	}
}