<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Deleter;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Tests\BaseTestCase;

class DeleterTest extends BaseTestCase
{
    private Deleter $deleter;

    protected function setUp(): void
    {
        $this->deleter = new Deleter();
    }

    public function testWillNotDeleteRootNode(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);

        $this->deleter->delete($rootNode, Node::ROOT_LABEL);
        $this->assertNodeRecursivelyEqual(
            new Node(Node::ROOT_LABEL),
            $rootNode
        );
    }

    public function testWillDeleteLeaf(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $rootNode,
            'test',
            'test'
        );

        $this->deleter->delete(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            new Node(Node::ROOT_LABEL),
            $rootNode
        );
    }

    public function testWillNotDeleteAsNoMatchingNode(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $rootNode,
            'test',
            'test'
        );
        $expectedNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $expectedNode,
            'test',
            'test'
        );

        $this->deleter->delete(
            $rootNode,
            't'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWontDeleteIntermediaryNode(): void
    {
        $expectedNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $expectedNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $rootNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );

        $this->deleter->delete(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteIntermediaryNodesLeaf(): void
    {
        $expectedNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $expectedNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $rootNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );
        $this->addLeafToNode(
            $testNode,
            'test',
            ''
        );

        $this->deleter->delete(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteLeafAndTransformIntermediaryNodeToLeaf(): void
    {
        $expectedNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $expectedNode,
            'test',
            'test'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $rootNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );
        $this->addLeafToNode(
            $testNode,
            'test',
            ''
        );

        $this->deleter->delete(
            $rootNode,
            'tester'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteOneOfLeafsAndCollapseIntermediaryNodeWhichGrowsFromRoot(): void
    {
        $expectedNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $expectedNode,
            'testing',
            'testing'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $rootNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );

        $this->deleter->delete(
            $rootNode,
            'tester'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteOneOfLeafsAndCollapseIntermediaryNode(): void
    {
        $expectedNode = new Node(Node::ROOT_LABEL);
        $tNode = $this->addLeafToNode(
            $expectedNode,
            't',
            't'
        );
        $this->addLeafToNode(
            $tNode,
            'testing',
            'esting'
        );
        $this->addLeafToNode(
            $tNode,
            't',
            ''
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $tNode = $this->addLeafToNode(
            $rootNode,
            't',
            't'
        );
        $testNode = $this->addLeafToNode(
            $tNode,
            'test',
            'est'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );
        $this->addLeafToNode(
            $tNode,
            't',
            ''
        );

        $this->deleter->delete(
            $rootNode,
            'tester'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testDeleteFirstIntermediaryNode(): void
    {
        $expectedNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $expectedNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );
        $this->addLeafToNode(
            $testNode,
            'test',
            ''
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $tNode = $this->addLeafToNode(
            $rootNode,
            't',
            't'
        );
        $testNode = $this->addLeafToNode(
            $tNode,
            'test',
            'est'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );
        $this->addLeafToNode(
            $tNode,
            't',
            ''
        );
        $this->addLeafToNode(
            $testNode,
            'test',
            ''
        );

        $this->deleter->delete(
            $rootNode,
            't'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testDeleteTwoLeafsOfRoot(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $rootNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $rootNode,
            'west',
            'west'
        );

        $this->deleter->delete(
            $rootNode,
            'test'
        );
        $this->deleter->delete(
            $rootNode,
            'west'
        );

        $this->assertNodeRecursivelyEqual(
            new Node(Node::ROOT_LABEL),
            $rootNode
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

    private function addLeafToNode(
        Node $sourceNode,
        string $targetNodeLabel,
        string $edgeLabel
    ): Node {
        $targetNode = new Node($targetNodeLabel);
        $edge = new Edge(
            $edgeLabel,
            $targetNode
        );
        $sourceNode->addEdge($edge);

        return $targetNode;
    }
}
