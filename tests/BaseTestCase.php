<?php

declare(strict_types=1);

namespace fafoa\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;
use fafoa\RadixTrie\Entity\Edge;
use fafoa\RadixTrie\Entity\Node;

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
        $sourceNode->addLeaf($edgeLabel, $targetNode);

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
        $this->assertEquals(
            $expectedNode->isValue(),
            $actualNode->isValue()
        );

        $expectedEdges = $expectedNode->getEdges();
        $actualEdges = $actualNode->getEdges();

        ksort($expectedEdges);
        ksort($actualEdges);
        $this->assertEquals(
            array_keys($expectedEdges),
            array_keys($actualEdges)
        );

        foreach (array_keys($expectedEdges) as $edgeKey) {
            $this->assertNodeRecursivelyEqual(
                $expectedEdges[$edgeKey],
                $actualEdges[$edgeKey]
            );
        }
    }
}
