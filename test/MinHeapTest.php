<?php

use PHPUnit\Framework\TestCase;
use Vicent\MinHeap;

require_once __DIR__ . '/../src/MinHeap.php';
require_once __DIR__ . '/../vendor/autoload.php';

class MinHeapTest extends TestCase {
    public function testAll() {
        $values = [-8, 17, -24, 1231, INF, 3.2, 123.123, 0];
        $heap = new MinHeap($values);
        $this->assertEquals(count($values), $heap->count());
        
        sort($values);
        foreach($values as $value) {
            $this->assertFalse($heap->empty());
            $this->assertEquals($value, $heap->get());
            $this->assertEquals($value, $heap->pop());
        }

        $this->assertTrue($heap->empty());
        $this->assertNull($heap->get());
        $this->assertNull($heap->pop());
    }
}