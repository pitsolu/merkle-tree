<?php

namespace Merkle;

use Strukt\Type\Json;

class Leaf implements LeafInterface{

	private $data;

	public function __construct(Array $data){

		$this->data = $data;
	}

	public function getData(){

		return $this->data;
	}

	public function __toString(){

		return Json::encode($this->data);
	}
}