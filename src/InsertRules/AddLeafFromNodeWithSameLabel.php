<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;

class AddLeafFromNodeWithSameLabel extends BaseRule
{
    public function supports(
        Node $node,
        string $word
    ): bool {
        return
            $node->getLabel() === $word
            && !$node->isLeaf()
            && !$this->hasSameLabelLeaf($node)
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
