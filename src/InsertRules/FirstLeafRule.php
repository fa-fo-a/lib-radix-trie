<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;

class FirstLeafRule extends BaseRule
{
    public function supports(
        Node $node,
        string $word
    ): bool {
        return
            $node->isRoot()
            && $node->isLeaf()
        ;
    }

    public function apply(
        Node $node,
        string $word
    ): void {
        $this->addNewEdge(
            $node,
            $word
        );
    }
}
