<?php

namespace Merkle;

use Strukt\Util\Json;

class LeafItem implements LeafInterface{

	private $data;

	public function __construct(string $data){

		$this->data = $data;
	}

	public function getData(){

		return $this->data;
	}

	public function __toString(){

		return $this->data;
	}
}