<?php

class TreeExceptionTest extends PHPUnit_Framework_TestCase{

    /**
     * @expectedException     	 Exception
     * @expectedExceptionMessage Merkle Tree hash function must be set first!
     */
    public function testExceptionOnLeaf(){

      	new Merkle\Leaf([]);
    }
}