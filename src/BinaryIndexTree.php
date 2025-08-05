<?php
namespace Vicent;

class BinaryIndexTreeIterator implements \Iterator {
    private $bit = null;
    private $index = 0;
    public function __construct(BinaryIndexTree $bit) { $this->bit = $bit; }
    public function current(): int|float { return $this->bit->get($this->index); }
    public function next(): void { $this->index++; }
    public function key(): int { return $this->index; }
    public function valid(): bool { return $this->index >= 0 && $this->index < $this->bit->count(); }
    public function rewind(): void { $this->index = 0; }
}

/**
 * Binary Index Tree (Fenwick Tree) implementation with range updates and queries.
 * O(log n) time complexity for update(), set(), sum(), prefixSum() and add(). 
 */
class BinaryIndexTree implements \IteratorAggregate, \Countable, \ArrayAccess, \Stringable {
    protected $data = [0];

    public function __construct(int $elements = 0) {
        for($i = 0; $i < $elements; $i++)
            $this->data[] = 0;
    }

    /** Creates a BinaryIndexTree from another BinaryIndexTree, copying it. */
    public static function fromObject(BinaryIndexTree $other): BinaryIndexTree {
        $n = $other->count();
        $bit = new BinaryIndexTree($n);
        for($i = 1; $i <= $n; $i++)
            $bit->data[$i] = $other->data[$i];
        return $bit;
    }

    /** Creates a BinaryIndexTree from a sequence of values */
    public static function fromValues(array|\Traversable $values) {
        $bit = new BinaryIndexTree();
        foreach($values as $value)
            $bit->add($value);
        return $bit;
    }

    /** Computes the sum of elements in the range [0,$idx). */
    public function prefixSum(int $idx): int|float {
        $result = 0;
        while ($idx > 0) {
            $result += $this->data[$idx];
            $idx -= $idx & -$idx;
        }
        return $result;
    }

    /** Computes the sum of elements in the range [$l, $r). */
    public function sum(int $l, int $r): int|float {
        return $this->prefixSum($r) - $this->prefixSum($l);
    }

    /** Gets the value stored at $idx. */
    public function get(int $idx): int|float {
        return $this->sum($idx, $idx+1);
    }

    public function getAll(): array {
        $values = [];
        $n = $this->count();
        for($i = 0; $i < $n; $i++)
            $values[$i] = $this->get($i);
        return $values;
    }

    /** Updates the value stored at $idx, adding $val to it. */
    public function update(int $idx, int|float $val) {
        $idx++;
        $n = count($this->data);
        while($idx < $n) {
            $this->data[$idx] += $val;
            $idx += $idx & -$idx;
        }
    }

    /** Returns the amount of elements in this BinaryIndexTree. */
    public function count(): int {
        return count($this->data) - 1;
    }

    /** Sets the value of an element. */
    public function set(int $idx, int|float $val) {
        if($idx > $this->count())
            throw new \InvalidArgumentException("Cannot set() {$idx}, as it is outside of the BinaryIndexTree range. Use add() to add new values to the tree.");
        $this->update($idx, $val - $this->get($idx));
    }

    /** Appends a new element to the end of the BinaryIndexTree with a specific value. */
    public function add(int|float $newVal) {
        $this->data[] = 0;
        $this->set($this->count()-1, $newVal);
    }

    public function __toString(): string {
        return print_r($this->getAll(), true);
    }

    public function getIterator(): BinaryIndexTreeIterator {
        return new BinaryIndexTreeIterator($this);
    }

    public function offsetExists(mixed $offset): bool {
        return isset($array[$offset]);
    }

    public function offsetGet(mixed $offset): int|float {
        if(!is_integer($offset))
            throw new \InvalidArgumentException("Index of a BinaryIndexTree must be an integer number, supplied {$offset}");
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        if(!is_integer($offset))
            throw new \InvalidArgumentException("Index of a BinaryIndexTree must be an integer number, supplied {$offset}");
        if(!is_numeric($value))
            throw new \InvalidArgumentException("Value of a BinaryIndexTree must be numeric, supplied {$offset}");
        $this->set($offset, $value);
    }
    public function offsetUnset(mixed $offset): void {
        throw new \InvalidArgumentException("Unsupported operation, values can't be removed from a BinaryIndexTree.");
    }
}
