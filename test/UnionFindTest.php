<?php

use PHPUnit\Framework\TestCase;
use Vicent\UnionFind;

require_once __DIR__ . '/../src/UnionFind.php';
require_once __DIR__ . '/../vendor/autoload.php';

class UnionFindTest extends TestCase {
    public function testConstructors() {
        $this->assertEquals([], new UnionFind()->findAll());
        $this->assertEquals([], UnionFind::fromParents([])->findAll());
        $this->assertEquals([], UnionFind::fromObject(new UnionFind())->findAll());
        $this->assertEquals([0,1,2,3,4,5,6,7,8,9], new UnionFind(10)->findAll());
        $this->assertEquals([0,1,2,3,4,5], UnionFind::fromParents([0,1,2,3,4,5])->findAll());
        $this->assertEquals([0,1,2,3,4,5,6], UnionFind::fromObject(new UnionFind(7))->findAll());
        $this->assertEquals([1,1,1,1,1], UnionFind::fromParents([1,1,1,1,1])->findAll());
        $this->assertEquals([3,2,2,3,3], UnionFind::fromObject(UnionFind::fromParents([3,2,2,3,3]))->findAll());
    }
    public function testCount() {
        $this->assertEquals(0, new UnionFind()->count());
        $this->assertEquals(7, new UnionFind(7)->count());
        $this->assertEquals(42, new UnionFind(42)->count());
    }
    public function testAdd() {
        $uf = new UnionFind();
        $uf->add(0);
        $uf->add();
        $uf->add(2);
        $this->assertEquals([0,1,2], $uf->findAll());
    }
    public function testFind() {
        $uf = UnionFind::fromParents([1,1,4,1,4,5]);
        $this->assertEquals($uf->find(0), 1);
        $this->assertEquals($uf->find(1), 1);
        $this->assertEquals($uf->find(2), 4);
        $this->assertEquals($uf->find(3), 1);
        $this->assertEquals($uf->find(4), 4);
        $this->assertEquals($uf->find(5), 5);
    }
    public function testUnion() {
        $uf = new UnionFind(6);
        $uf->union(1,2);
        $uf->union(1,3);
        $uf->union(0,4);
        $this->assertEquals($uf->find(1), $uf->find(2));
        $this->assertEquals($uf->find(1), $uf->find(3));
        $this->assertEquals($uf->find(2), $uf->find(2));
        $this->assertEquals($uf->find(0), $uf->find(4));
        $this->assertTrue($uf->sameSet(1, 2));
        $this->assertTrue($uf->sameSet(1, 3));
        $this->assertTrue($uf->sameSet(2, 3));
        $this->assertTrue($uf->sameSet(0, 4));
        for($i = 0; $i < 5; $i++) {
            $this->assertNotEquals($uf->find(5), $uf->find($i));
            $this->assertFalse($uf->sameSet(5, $i));
        }
    }
}