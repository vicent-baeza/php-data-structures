<?php
namespace Vicent;

/**
 * A basic Trie implementation, with O(k) complexity for add() & contains().
 */
class Trie implements \Countable {
    protected $child = [];
    protected $final = false;

    /**
     * Constructs a new trie from a list of words.
     */
    public function __construct(array $words = []) {
        foreach($words as $word)
            $this->add($word);
    }

    /**
     * Adds a word to the trie.
     */
    public function add(string $word): void {
        $this->_add($word);
    }

    protected function _add(string $word, int $idx = 0): void {
        if($idx >= strlen($word)) {
            $this->final = true;
            return;
        }

        $c = $word[$idx];
        if(!isset($this->child[$c]))
            $this->child[$c] = new Trie();

        $this->child[$c]->_add($word, $idx + 1);
    }

    /**
     * Removes a word to the trie (if contained). Returns true if the word was in the Trie.
     */
    public function remove(string $word): void {
        $this->_remove($word);
    }

    protected function _remove(string $word, int $idx = 0): bool {
        if($idx >= strlen($word)) {
            $res = $this->final;
            $this->final = false;
            return $res;
        }

        $c = $word[$idx];
        if(!isset($this->child[$c]))
            return false;
        
        return $this->child[$c]->_remove($word, $idx + 1);
    }

    /**
     * Checks if a word is contained in the trie.
     */
    public function contains(string $word) {
        return $this->_contains($word);
    }

    protected function _contains(string $word, int $idx = 0): bool {
        if($idx >= strlen($word))
            return $this->final;

        $c = $word[$idx];
        if(!isset($this->child[$c]))
            return false;

        return $this->child[$c]->_contains($word, $idx + 1);
    }

    /**
     * The number of words contained in this trie.
     */
    public function count(): int {
        $count = $this->final ? 1 : 0;
        foreach($this->child as $node)
            $count += $node->count();

        return $count;
    }

    /**
     * Checks if any word in the trie contains the given prefix.
     */
    public function containsPrefix(string $prefix): bool {
        return $this->_containsPrefix($prefix);
    }

    protected function _containsPrefix(string $prefix, int $idx = 0): bool {
        if($idx >= strlen($prefix))
            return $this->final || $this->count() > 0;

        $c = $prefix[$idx];
        if(!isset($this->child[$c]))
            return false;

        return $this->child[$c]->_containsPrefix($prefix, $idx + 1);
    }
}
