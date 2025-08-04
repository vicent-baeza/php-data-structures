<?php
namespace Vicent;

/**
 * Union-Find (Disjoint-Set) implementation with path compression.
 * O(α(n)) time complexity for union() and find(). 
 */
class UnionFind implements \IteratorAggregate, \Countable, \ArrayAccess, \Stringable {
    protected $parent = [];

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
        foreach($parents as $key => $parent)
            $uf->add($parent, $key);
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
    public function add(int|string|null $parent = null, int|string|null $key = null): void {
        if(!isset($parent))
            $parent = $this->count();

        if(!isset($key))
            $this->parent[] = $parent;
        else if(isset($parent[$key]))
            throw new \InvalidArgumentException("Can't add {$key} to the UnionFind, it was already present.");
        else
            $this->parent[$key] = $parent;
    }

    /** Finds the representative (the root node) of the set that contains an element. Returns null if the element doesn't exist */
    public function find(int|string $element): int|string|null {
        if(!$this->exists($element))
            return null;
        if(!$this->isRepresentative($element))
            $this->parent[$element] = $this->find($this->parent[$element]);

        return $this->parent[$element];
    }

    /** Find the representative (the root node) of all given elements. */
    public function findMany(array $elements): array {
        $array = [];
        foreach($elements as $element)
            $array[$element] = $this->find($element);
        return $array;
    }

    /** Finds the representative (the root node) of all elements. */
    public function findAll(): array {
        return $this->findMany($this->elements());
    }

    /** Unites the sets containing two elements. */
    public function union(int|string $element1, int|string $element2): void {
        $element1 = $this->find($element1);
        $element2 = $this->find($element2);
        $this->_link($element1, $element2);
    }

    /** Unites all the sets that contain the given elements */
    public function unionMany(array $elements) {
        $elements = array_values($elements);
        shuffle($elements);
        $n = count($elements);
        for($i = 1; $i < $n; $i++)
            $this->union($elements[$i], $elements[$i-1]);
    }

    /** Checks if an element exists in this UnionFind. */
    public function exists(int|string $element): bool {
        return isset($this->parent[$element]);
    }

    /** Checks if an element is the representative of its set */
    public function isRepresentative(int|string $element): bool {
        return $this->parent[$element] === $element;
    }

    /** Checks if and element is in the set represented by $setRepresentative. */
    public function inSet(int|string $element, int|string $setRepresentative): bool {
        return $this->find($element) === $setRepresentative;
    }

    /** Checks if two elements are in the same set. */
    public function sameSet(int|string $element1, int|string $element2): bool {
        return $this->find($element1) === $this->find($element2);
    }

    /** Unites the sets represented by two elements. Throws an error if either element is not a representative of their set. */
    public function link(int|string $element1, int|string $element2): void {
        if(!$this->isRepresentative($element1) || !$this->isRepresentative($element2))
            throw new \InvalidArgumentException("To call link(), both elements must be the representative of their respective set.");
        
        $this->_link($element1, $element2);
    }

    /** Returns all the elements contained in this UnionFind. */
    public function elements(): array {
        return array_keys($this->parent);
    }

    private function _link(int|string $i, int|string $j): void {
        // Randomly shuffle union insertion (reduces complexity, see https://codeforces.com/blog/entry/21476)
        if(rand(0, 1) == 0) 
            $this->parent[$i] = $j;
        else
            $this->parent[$j] = $i;
    }

    // Countable interface:
    public function count(): int {
        return count($this->parent);
    }

    // IteratorAggregate interface:
    public function getIterator(): \Traversable {
        return new \ArrayObject($this->findAll()); // calls findAll() to find the representative of all elements
    }
    // Stringable interface:
    public function __toString(): string {
        $this->findAll();
        return print_r($this->parent, true);
    }

    /** Checks if an element exists. Shorthand for exists(). */
    public function offsetExists(mixed $offset): bool {
        return $this->exists($offset);
    }

    /** Finds the representative (the root node) of the set that contains this element. Shorthand for find(). */
    public function offsetGet(mixed $offset): int {
        return $this->find($offset);
    }

    /** Joins two sets containing the provided elements. Shorthand for union(). */
    public function offsetSet(mixed $offset, mixed $value): void {
        $this->union($offset, $value);
    }

    /** Unsupported operation, always throws an exception. Needed for ArrayAccess */
    public function offsetUnset(mixed $offset): void {
        throw new \InvalidArgumentException("Operation not supported: can't unset() values from an UnionFind");
    }
}