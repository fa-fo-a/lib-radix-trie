<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;

class RadixTrieTest extends TestCase
{
    private RadixTrie $trie;

    protected function setUp(): void
    {
        $this->trie = new RadixTrie();
    }

    public function test(): void
    {
        $this->trie->insert('test');
        $this->trie->insert('toaster');
        $this->trie->insert('toasting');
        $this->trie->insert('slow');
        $this->trie->insert('slowly');

        $this->assertEquals(
            [
                'test',
                'toaster',
                'toasting',
            ],
            $this->trie->find('t')
        );

        $this->assertEquals(
            [
                'slow',
                'slowly',
            ],
            $this->trie->find('s')
        );

        $this->assertEquals(
            [
                'toaster',
                'toasting',
            ],
            $this->trie->find('to')
        );

        $this->assertEquals(
            [
                'toaster',
            ],
            $this->trie->find('toaster')
        );
    }
}
