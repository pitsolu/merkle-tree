<?php

namespace Merkle;

class Leaf{

	private $data;
	private $hash;

	public function __construct(Array $data){

		$this->data = $data;
	}

	public function getData(){

		return $this->data;
	}

	public function __toString(){

		return json_encode($this->data);
	}
}