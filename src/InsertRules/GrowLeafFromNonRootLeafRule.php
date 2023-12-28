<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\Edge;

class GrowLeafFromNonRootLeafRule extends BaseRule
{
    public function supports(
        Node $node,
        string $word
    ): bool {
        return
            $node->isLeaf()
            && !$node->isRoot()
            && $this->getPartialMatchingEdge(
                $node,
                $word
            )
        ;
    }

    public function apply(
        Node $node,
        string $word
    ): void {
        $this->addNewEdge(
            $node,
            $node->getLabel()
        );
    }

    private function getPartialMatchingEdge(
        Node $baseNode,
        string $word
    ): ?Edge {
        $suffix = $this->stringHelper->getSuffix(
            $baseNode->getLabel(),
            $word
        );
        foreach ($baseNode->getEdges() as $edge) {
            $matchingAmount = $this->stringHelper->getCommonPrefixLength(
                $edge->getLabel(),
                $suffix
            );
            if (
                $matchingAmount > 0
                && $matchingAmount < strlen($edge->getLabel())
            ) {
                return $edge;
            }
        }

        return null;
    }
}
