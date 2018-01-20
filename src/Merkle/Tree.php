<?php

namespace Merkle;

class Tree{

	private $nodes;
	private $func;

	public function __construct(\Closure $func){

		$this->nodes = [];

		$this->hashRefl = new \ReflectionFunction($func);
	}

	public function doHash($data){

		return $this->hashRefl->invoke($data);
	}

	public function add(Leaf $data){

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