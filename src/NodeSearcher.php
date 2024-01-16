<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class NodeSearcher
{
    public function search(
        Node $rootNode,
        string $query
    ): ?Node {
        $currentNode = $rootNode;
        $currentLabel = $query;

        while (
            $currentNode !== null
            && !$currentNode->isLeaf()
            && strlen($currentLabel) > 0
        ) {
            $edge = $this->getMatchingEdge(
                $currentNode,
                $currentLabel
            );
            if ($edge === null) {
                return $currentNode;
            }

            $currentNode = $edge->getTargetNode();
            $currentLabel = substr($currentLabel, strlen($edge->getLabel()));
        }

        return $currentNode;
    }

    private function getMatchingEdge(
        Node $node,
        string $query
    ): ?Edge {
        foreach ($node->getEdges() as $edge) {
            $pos = strpos($query, $edge->getLabel());
            if ($pos === 0 && strlen($edge->getLabel()) > 0) {
                return $edge;
            }
        }

        return null;
    }

}

