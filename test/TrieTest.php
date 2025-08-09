<?php

use PHPUnit\Framework\TestCase;
use Vicent\Trie;

require_once __DIR__ . '/../src/Trie.php';
require_once __DIR__ . '/../vendor/autoload.php';

class TrieTest extends TestCase {
    public function testAll() {
        $words = ['aa', 'aab', 'ba', 'c', 'dfaa'];
        $fakeWords = ['ab', 'a', 'b', 'cc', 'dfa', 'dfaa ', 'aaa', 'aaba', '', 'f'];
        $trie = new Trie($words);
        $this->assertEquals(count($words), $trie->count());
        foreach($words as $word) {
            $this->assertTrue($trie->contains($word));
            $this->assertTrue($trie->containsPrefix($word));
        }
        foreach($fakeWords as $fakeWord)
            $this->assertFalse($trie->contains($fakeWord));

        $this->assertTrue($trie->containsPrefix(''));
        $this->assertTrue($trie->containsPrefix('a'));
        $this->assertTrue($trie->containsPrefix('b'));
        $this->assertTrue($trie->containsPrefix('c'));
        $this->assertTrue($trie->containsPrefix('d'));
        $this->assertTrue($trie->containsPrefix('df'));
        $this->assertTrue($trie->containsPrefix('dfa'));
        $this->assertFalse($trie->containsPrefix('aaa'));
        $this->assertFalse($trie->containsPrefix('ff'));
        $this->assertFalse($trie->containsPrefix('bb'));
        $this->assertFalse($trie->containsPrefix('g'));
    }
}