<?php

declare(strict_types=1);

namespace fafoa\RadixTrie;

use fafoa\RadixTrie\Entity\Node;

class NodeSearcher
{
    public function search(
        Node $rootNode,
        string $query
    ): Node {
        $currentNode = $rootNode;
        $currentLabel = $query;

        while (
            $currentNode !== null
            && !$currentNode->isLeaf()
        ) {
            $edge = $this->getMatchingEdge(
                $currentNode,
                $currentLabel
            );
            if ($edge === null) {
                return $currentNode;
            }

            $currentNode = $currentNode->getEdges()[$edge];
            $currentLabel = substr($currentLabel, strlen($edge));
        }

        return $currentNode;
    }

    private function getMatchingEdge(
        Node $node,
        string $query
    ): ?string {
        foreach ($node->getEdges() as $edge => $node) {
            $pos = strpos($query, $edge);
            if ($pos === 0) {
                return $edge;
            }
        }

        return null;
    }
}
