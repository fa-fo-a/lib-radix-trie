<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class BaseTestCase extends TestCase
{
    protected function assertArraysHaveEqualDataset(
        array $expectedArray,
        array $actualArray,
        string $explanation = ''
    ): void {
        sort($expectedArray);
        sort($actualArray);

        $this->assertEquals(
            $expectedArray,
            $actualArray,
            $explanation
        );
    }

    protected function addLeafToNode(
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

    protected function assertNodeRecursivelyEqual(
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
