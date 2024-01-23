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

    public function testWillNotDeleteAsNoMatchingNode(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $trie = new RadixTrie(
            $rootNode
        );
        $trie->insert('test');

        $trie->delete('t');
        $this->assertEquals(
            ['test'],
            $trie->find('test')
        );
    }

    public function testWontDeleteIntermediaryNode(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $trie = new RadixTrie(
            $rootNode
        );
        $trie->insert('testing');
        $trie->insert('tester');

        $trie->delete('test');
        $this->assertEquals(
            [
                'testing',
                'tester',
            ],
            $trie->find('test')
        );
    }

    public function testWillDeleteIntermediaryNodesLeaf(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $trie = new RadixTrie(
            $rootNode
        );
        $trie->insert('testing');
        $trie->insert('tester');
        $trie->insert('test');

        $trie->delete('test');
        $this->assertEquals(
            [
                'testing',
                'tester',
            ],
            $trie->find('test')
        );
    }

    public function testWillDeleteLeafAndTransformIntermediaryNodeToLeaf(): void
    {
        $expectedResult = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $expectedResult->insert('test');

        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $trie->insert('test');
        $trie->insert('tester');

        $trie->delete('tester');
        $this->assertEquals(
            var_export(
                $expectedResult->getRootNode(),
                true
            ),
            var_export(
                $trie->getRootNode(),
                true
            )
        );
    }

    public function testWillDeleteOneOfLeafsAndCollapseIntermediaryNode(): void
    {
        $expectedResult = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $expectedResult->insert('testing');

        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $trie->insert('testing');
        $trie->insert('tester');

        $trie->delete('tester');
        $this->assertEquals(
            var_export(
                $expectedResult->getRootNode(),
                true
            ),
            var_export(
                $trie->getRootNode(),
                true
            )
        );
    }
}
