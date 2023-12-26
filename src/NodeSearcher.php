<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class NodeSearcher
{
    private StringHelper $stringHelper;

    public function __construct(
    ) {
        $this->stringHelper = new StringHelper();
    }

    public function search(
        Node $rootNode,
        string $query
    ): ?Node {
        $currentNode = $rootNode;
        $currentEdgeLength = 0;

        while (
            $currentNode !== null
            && !$currentNode->isLeaf()
            && $currentEdgeLength < strlen($query)
        ) {
            $edge = $this->getMatchingEdge(
                $currentNode,
                $query
            );
            if ($edge === null) {
                return $currentNode;
            }

            $currentNode = $edge->getTargetNode();
            $currentEdgeLength = strlen($edge->getLabel());
        }

        return $currentNode;
    }

    private function getMatchingEdge(
        Node $node,
        string $query
    ): ?Edge {
        $leftover = $this->stringHelper->getSuffix(
            $node->getLabel(),
            $query
        );
        foreach ($node->getEdges() as $edge) {
            $matchingAmount = $this->stringHelper->getCommonPrefixLength(
                $leftover,
                $edge->getLabel()
            );
            if (
                $matchingAmount > 0
                && $matchingAmount === strlen($edge->getLabel())
            ) {
                return $edge;
            }
        }

        return null;
    }

}

