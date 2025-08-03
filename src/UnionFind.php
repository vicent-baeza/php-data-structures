<?php
namespace Vicent;

/**
 * Union-Find (Disjoint-Set) implementation with path compression.
 * O(α(n)) time complexity for union() and find(). 
 */

class UnionFind implements \IteratorAggregate, \Countable {
    public $parent = [];

    /**
     * Constructs a new UnionFind. If a parent is not specified, then each element will be added in a new set.
     */
    function __construct(int $size = 0) {
        for($i = 0; $i < $size; $i++)
            $this->add();
    }

    /**
     * Constructs a new UnionFind from a list of parents.
     */
    public static function fromParents(array|\Traversable $parents) {
        $uf = new UnionFind();
        foreach($parents as $p)
            $uf->add($p);
        return $uf;
    }

    /**
     * Constructs an UnionFind from another UnionFind.
     */
    public static function fromObject(UnionFind $uf) {
        return UnionFind::fromParents($uf->parent);
    }

    /**
     * Adds a new element to the structure. If a parent is not specified, then the element will be in a new set.
     */
    public function add(?int $parentIdx = null) {
        if($parentIdx === null)
            $parentIdx = $this->count();
        else if($parentIdx < 0)
            throw new \InvalidArgumentException("Parent index cannot be negative. Parent index was: {$parentIdx}");
        
        $this->parent[] = $parentIdx;
    }

    /**
     * Finds the representative (the root node) of the set that contains [idx].
     */
    public function find(int $idx): int {
        if(!$this->isRepresentative($idx))
            $this->parent[$idx] = $this->find($this->parent[$idx]);

        return $this->parent[$idx];
    }

    /**
     * Unites the sets containing [i] and [j].
     */
    public function union(int $i, int $j) {
        $i = $this->find($i);
        $j = $this->find($j);
        $this->_link($i, $j);
    }

    /**
     * Checks if [idx] is the representative of the set that contains it.
     */
    public function isRepresentative(int $idx): bool {
        return $this->parent[$idx] == $idx;
    }

    /**
     * Checks if two elements are in the same set.
     */
    public function sameSet(int $i, int $j): bool {
        return $this->find($i) === $this->find($j);
    }

    /**
     * Unites the sets represented by [i] and [j]. Throws an error if [i] or [j] are not representatives. 
     */
    public function link(int $i, int $j) {
        if(!$this->isRepresentative($i) || !$this->isRepresentative($j))
            throw new \InvalidArgumentException("To call link(), [i] and [j] must be representative of their sets.");
        
        $this->_link($i, $j);
    }

    /**
     * Finds the representative of all elements.
     */
    public function findAll(): array {
        $n = $this->count();
        for($i = 0; $i < $n; $i++)
            $this->find($i);

        return $this->parent;
    }

    public function count(): int {
        return count($this->parent);
    }

    public function getIterator(): \Traversable {
        return new \ArrayObject($this->findAll()); // calls findAll() to find the representative of all elements
    }

    private function _link(int $i, int $j) {
        // Randomly shuffle union insertion (reduces complexity, see https://codeforces.com/blog/entry/21476)
        if(rand(0, 1) == 0) 
            $this->parent[$i] = $j;
        else
            $this->parent[$j] = $i;
    }
}