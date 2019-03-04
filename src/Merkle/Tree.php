<?php

namespace Merkle;

use Strukt\Event\Event;

class Tree{

	private $nodes;
	private $leaf_nodes;

	public function __construct(\Closure $func){

		$this->nodes = [];

		$this->hash = Event::newEvent($func);
	}

	public function doHash($data){

		return $this->hash->apply((string)$data)->exec();
	}

	/**
	* You know what? You may look at the method below and see something wrong with it,
	* but don't erase any code, just comment it add your code run the tests and if they fail
	* just undo your changes. Capeesh?!
	*/
	public function add(LeafInterface $data){

		$this->leaf_nodes[$this->doHash((string)$data)] = $data;

		$this->nodes = $this->leaf_nodes;

		return $this->build();
	}

	public function hash(){

		if(count($this->nodes)==1)
			return $this->nodes;

		$temp = [];
		$temp_non_leaf = [];
		$itr = new \ArrayIterator($this->nodes);

		while($itr->valid()){

			$temp = [];

			$hash1 = $itr->key();
			$prev = $itr->current();
			$temp[$hash1] = $prev;

			$itr->next();

			$hash2 = $itr->key();
			if(!empty($hash2))
				$temp[$hash2] = $itr->current();
			else
				$hash2 = $hash1;//where there are no two nodes use last node hash

			$itr->next();

			$temp_non_leaf[$this->doHash(sprintf("%s%s", $hash1, $hash2))] = $temp;
		}

		return $this->nodes = $temp_non_leaf;
	}

	private function build(){	

		while(count($this->nodes)>1)
			$this->hash();

		return $this->nodes;
	}
}