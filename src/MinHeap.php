<?php
namespace Vicent;

/**
 * A binary min-heap implementation, with O(log n) complexity for add() & pop().
 */
class MinHeap implements \Countable {
    protected $data = [];

    /** Gets the index of the parent of a node from its index. */
    public static function parent(int $idx): int { return intdiv($idx-1, 2); }
    /** Gets the index of the left child of a node from its index. */
    public static function left(int $idx): int { return 2*$idx + 1; }
    /** Gets the index of the right child of a node from its index. */
    public static function right(int $idx): int { return 2*$idx + 2; }

    /** Constructs a new heap from a list of elements. */
    public function __construct(array $elements = []) {
        foreach($elements as $element)
            $this->add($element);
    }

    /** The number of elements in the heap. */ 
    public function count(): int {
        return count($this->data);
    }

    /** Checks if the heap is empty. */
    public function empty(): bool {
        return empty($this->data);
    }

    /** Auxiliary function to swap two elements from $data */
    protected function swap(int $i, int $j) {
        $temp = $this->data[$i];
        $this->data[$i] = $this->data[$j];
        $this->data[$j] = $temp;
    }

    /** Adds an element to the heap. */
    public function add(int|float $element): void {
        // add element at the end
        $this->data[] = $element;

        // restore the min-heap property
        $i = $this->count() - 1;
        while($i != 0 && $this->data[$this->parent($i)] > $this->data[$i]) {
            $this->swap($i, $this->parent($i));
            $i = $this->parent($i);
        }
    }

    /** Maintains the min-heap property when an element is deleted. */
    protected function _heapify(int $i = 0): void {
        $l = $this->left($i);
        $r = $this->right($i);
        $n = $this->count();
        $min = $i;
        if($l < $n && $this->data[$l] < $this->data[$min]) // check if bigger than $l
            $min = $l;
        if($r < $n && $this->data[$r] < $this->data[$min]) // check if bigger than $r
            $min = $r;

        if($min != $i) { // if bigger, swap with smaller node
            $this->swap($i, $min);
            $this->_heapify($min);
        }
    }
    
    /** Gets the smallest element from the heap & removes it from the heap. Returns null if the heap is empty. */
    public function pop(): int|float|null {
        if($this->empty())
            return null;

        $root = $this->data[0];
        $lastIdx = $this->count()-1;
        $this->swap(0, $lastIdx);
        unset($this->data[$lastIdx]);
        $this->_heapify();

        return $root;
    }

    /** Gets the smallest element from the heap. Returns null if the heap is empty. */
    public function get(): int|float|null {
        if($this->empty())
            return null;

        return $this->data[0];
    }
}
