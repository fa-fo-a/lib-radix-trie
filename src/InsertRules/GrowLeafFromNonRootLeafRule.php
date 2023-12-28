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
}
