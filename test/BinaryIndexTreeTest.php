<?php
use PHPUnit\Framework\TestCase;
use Vicent\BinaryIndexTree;
use Vicent\UnionFind;

require_once __DIR__ . '/../src/BinaryIndexTree.php';
require_once __DIR__ . '/../vendor/autoload.php';

class BinaryIndexTreeTest extends TestCase {
    public function testConstructors() {
        $bit = new BinaryIndexTree();
        $this->assertEquals(0, $bit->count());
        $bit = new BinaryIndexTree(13);
        $this->assertEquals(13, $bit->count());
        for($i = 0; $i < 13; $i++)
            $this->assertEquals(0, $bit->get($i));
        $this->assertEquals(21, BinaryIndexTree::fromObject(new BinaryIndexTree(21))->count());
    }
    public function testUpdate() {
        $bit = new BinaryIndexTree(6);
        $bit->update(1, 3);
        $bit->update(2, 24);
        $bit->update(4, -8);
        $this->assertEquals(0, $bit->prefixSum(0));
        $this->assertEquals(0, $bit->prefixSum(1));
        $this->assertEquals(3, $bit->prefixSum(2));
        $this->assertEquals(27, $bit->prefixSum(3));
        $this->assertEquals(27, $bit->prefixSum(4));
        $this->assertEquals(19, $bit->prefixSum(5));
        $this->assertEquals(19, $bit->sum(0, 5));
        $this->assertEquals(16, $bit->sum(2, 5));
        $this->assertEquals(27, $bit->sum(1, 3));
        $this->assertEquals(0, $bit->sum(3, 4));
        $this->assertEquals(0, $bit->get(0));
        $this->assertEquals(3, $bit->get(1));
        $this->assertEquals(24, $bit->get(2));
        $this->assertEquals(0, $bit->get(3));
        $this->assertEquals(-8, $bit->get(4));
        $this->assertEquals(0, $bit->get(5));
    }
    public function testAddSet() {
        $bit = new BinaryIndexTree(6);
        $bit->update(3, 21);
        $bit->set(3, -9);
        $this->assertEquals(-9, $bit->get(3));
        $bit->set(3, 10);
        $this->assertEquals(10, $bit->get(3));
        $bit->add(41);
        $this->assertEquals(7, $bit->count());
        $this->assertEquals(41, $bit->get(6)); 
    }
}