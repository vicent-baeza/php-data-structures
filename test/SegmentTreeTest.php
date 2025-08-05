<?php
use PHPUnit\Framework\TestCase;
use Vicent\MinSegmentTree;
use Vicent\UnionFind;

require_once __DIR__ . '/../src/SegmentTree.php';
require_once __DIR__ . '/../vendor/autoload.php';

class SegmentTreeTest extends TestCase {
    public function testConstructors() {
        $tree = new MinSegmentTree([1,4,7,10,5]);
        $this->assertEquals([1,4,7,10,5], $tree->getAll());
    }
    public function testQuery() {
        $tree = new MinSegmentTree([1,4,7,10,5]);
        $tree->set(1, -2);
        $tree->set(2, 24);
        $tree->set(3, -8);
        $this->assertEquals([1,-2,24,-8,5], $tree->getAll());

        $this->assertEquals(1, $tree->query(0, 1));
        $this->assertEquals(-2, $tree->query(1, 2));
        $this->assertEquals(24, $tree->query(2, 3));
        $this->assertEquals(-8, $tree->query(3, 4));
        $this->assertEquals(5, $tree->query(4, 5));

        $this->assertEquals(-8, $tree->query(0, 5));
        $this->assertEquals(-8, $tree->query(1, 5));
        $this->assertEquals(-8, $tree->query(2, 5));
        $this->assertEquals(-8, $tree->query(3, 5));
        $this->assertEquals(-8, $tree->query(0, 4));
        $this->assertEquals(-8, $tree->query(1, 4));
        $this->assertEquals(-8, $tree->query(2, 4));
        $this->assertEquals(-2, $tree->query(0, 3));
        $this->assertEquals(-2, $tree->query(1, 3));
        $this->assertEquals(-2, $tree->query(0, 2));

        $this->assertEquals($tree->startValue(), $tree->query(0, 0));
        $this->assertEquals($tree->startValue(), $tree->query(1, 1));
        $this->assertEquals($tree->startValue(), $tree->query(2, 2));
        $this->assertEquals($tree->startValue(), $tree->query(3, 3));
        $this->assertEquals($tree->startValue(), $tree->query(4, 4));
    }
}