<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Tests\BaseTestCase;

class RadixTrieDeleteTest extends BaseTestCase
{
    public function testWillNotDeleteRootNode(): void
    {
        // '' => (test) => test => (er) => tester
        //                      => () => test
        $rootNode = new Node(Node::ROOT_LABEL);
        $trie = new RadixTrie(
            $rootNode
        );

        $trie->delete('');
        $this->assertEquals(
            $rootNode,
            $trie->getRootNode()
        );
    }

    public function testWillDeleteLeaf(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $trie = new RadixTrie(
            $rootNode
        );
        $trie->insert('test');

        $trie->delete('test');
        $this->assertEquals(
            [],
            $trie->find('test')
        );
    }
}
