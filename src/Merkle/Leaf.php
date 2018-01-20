<?php

namespace Merkle;

class Leaf{

	private $data;
	private $hash;

	public function __construct(Array $data){

		$this->data = $data;
		$this->hash = Tree::doHash(json_encode($data));
	}

	public function getHash(){

		return $this->hash;
	}

	public function getData(){

		return $this->data;
	}
}