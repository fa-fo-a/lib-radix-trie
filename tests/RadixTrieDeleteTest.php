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

        $this->assertNodeRecursivelyEqual(
            $expectedResult->getRootNode(),
            $trie->getRootNode()
        );
    }

    public function testWillDeleteOneOfLeafsAndCollapseIntermediaryNodeWhichGrowsFromRoot(): void
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

        $this->assertNodeRecursivelyEqual(
            $expectedResult->getRootNode(),
            $trie->getRootNode(),
        );
    }

    public function testWillDeleteOneOfLeafsAndCollapseIntermediaryNode(): void
    {
        $expectedResult = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $expectedResult->insert('t');
        $expectedResult->insert('testing');

        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $trie->insert('t');
        $trie->insert('testing');
        $trie->insert('tester');

        $trie->delete('tester');

        $this->assertNodeRecursivelyEqual(
            $expectedResult->getRootNode(),
            $trie->getRootNode()
        );
    }

    public function testDeleteFirstIntermediaryNode(): void
    {
        $expectedResultTrie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $expectedResultTrie->insert('test');
        $expectedResultTrie->insert('testing');
        $expectedResultTrie->insert('tester');

        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $trie->insert('t');
        $trie->insert('test');
        $trie->insert('testing');
        $trie->insert('tester');

        // @todo catch edge case due to root node issue?
        $trie->delete('t');

        $this->assertNodeRecursivelyEqual(
            $expectedResultTrie->getRootNode(),
            $trie->getRootNode(),
        );
    }

    private function assertNodeRecursivelyEqual(
        Node $expectedNode,
        Node $actualNode
    ): void {
        $this->assertEquals(
            $expectedNode->getLabel(),
            $actualNode->getLabel()
        );

        $expectedEdges = [];
        foreach ($expectedNode->getEdges() as $edge) {
            $expectedEdges[$edge->getLabel()] = $edge;
        }
        $actualEdges = [];
        foreach ($actualNode->getEdges() as $edge) {
            $actualEdges[$edge->getLabel()] = $edge;
        }

        ksort($expectedEdges);
        ksort($actualEdges);
        $this->assertEquals(
            array_keys($expectedEdges),
            array_keys($actualEdges)
        );

        foreach (array_keys($expectedEdges) as $edgeKey) {
            $this->assertNodeRecursivelyEqual(
                $expectedEdges[$edgeKey]->getTargetNode(),
                $actualEdges[$edgeKey]->getTargetNode()
            );
        }
    }
}
