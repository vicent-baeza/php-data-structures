<?php
namespace Vicent;

class SegmentTreeIterator implements \Iterator {
    private $tree = null;
    private $index = 0;
    public function __construct(SegmentTree $tree) { $this->tree = $tree; }
    public function current(): mixed { return $this->tree->get($this->index); }
    public function next(): void { $this->index++; }
    public function key(): int { return $this->index; }
    public function valid(): bool { return $this->index >= 0 && $this->index < $this->tree->count(); }
    public function rewind(): void { $this->index = 0; }
}

/**
 * Segment Tree implementation with point updates & range queries.
 * O(log n) time complexity for set() & query().
 * This abstract class requires that the functions startValue() and operation() be implemented. See MinSegmentTree, MaxSegmentTree, SumSegmentTree & ProductSegmentTree for examples.
 */
abstract class SegmentTree implements \IteratorAggregate, \Countable, \ArrayAccess, \Stringable {
    protected $data;

    public static abstract function startValue(): mixed;
    public static abstract function operation(mixed $a, mixed $b): mixed;

    public function __construct(array $values) {
        $n = count($values);
        $this->data = array_fill(0, 2*$n, 0);
        for($i = 0; $i < $n; $i++)
            $this->data[$n + $i] = $values[$i];
        for($i = $n - 1; $i >= 1; $i--)
            $this->data[$i] = $this->operation($this->data[2*$i], $this->data[2*$i+1]);
    }

    public function count(): int {
        return intdiv(count($this->data), 2);
    }

    public function get(int $idx): mixed {
        return $this->data[$idx + $this->count()];
    }

    public function getAll(): array {
        $array = [];
        $n = $this->count();
        for($i = 0; $i < $n; $i++)
            $array[$i] = $this->data[$i + $n];
        return $array;
    }

    public function set(int $idx, mixed $value) {
        $idx += $this->count();
        $this->data[$idx] = $value;
        while($idx > 1) {
            $idx = intdiv($idx, 2);
            $this->data[$idx] = $this->operation($this->data[2*$idx], $this->data[2*$idx+1]);
        }
    }

    public function query(int $l, int $r): mixed {
        $l += $this->count();
        $r += $this->count();
        $result = $this->startValue();
        while($l < $r) {
            if($l % 2 == 1) {
                $result = $this->operation($result, $this->data[$l]);
                $l++;
            }
            if($r % 2 == 1) {
                $r--;
                $result = $this->operation($this->data[$r], $result);
            }
            $l = intdiv($l, 2);
            $r = intdiv($r, 2);
        }
        return $result;
    }

    public function __toString(): string {
        return print_r($this->getAll(), true);
    }

    public function getIterator(): SegmentTreeIterator {
        return new SegmentTreeIterator($this);
    }

    public function offsetExists(mixed $offset): bool {
        if(!is_integer($offset))
            return false;
        return $offset >= 0 && $offset < $this->count();
    }

    public function offsetGet(mixed $offset): int|float {
        if(!is_integer($offset))
            throw new \InvalidArgumentException("Index of a SegmentTree must be an integer number, supplied {$offset}");
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        if(!is_integer($offset))
            throw new \InvalidArgumentException("Index of a SegmentTree must be an integer number, supplied {$offset}");
        $this->set($offset, $value);
    }
    public function offsetUnset(mixed $offset): void {
        throw new \InvalidArgumentException("Unsupported operation, values can't be removed from a SegmentTree.");
    }
}

class MinSegmentTree extends SegmentTree {
    public static function startValue(): mixed { return INF; }
    public static function operation(mixed $a, mixed $b): mixed { return min($a, $b); }
}
class MaxSegmentTree extends SegmentTree {
    public static function startValue(): mixed { return -INF; }
    public static function operation(mixed $a, mixed $b): mixed { return max($a, $b); }
}
class SumSegmentTree extends SegmentTree {
    public static function startValue(): mixed { return 0; }
    public static function operation(mixed $a, mixed $b): mixed { return $a + $b; }
}
class ProductSegmentTree extends SegmentTree {
    public static function startValue(): mixed { return 1; }
    public static function operation(mixed $a, mixed $b): mixed { return $a * $b; }
}