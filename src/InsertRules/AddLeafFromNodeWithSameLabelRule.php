<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;

class AddLeafFromNodeWithSameLabelRule extends BaseRule
{
    public function supports(
        Node $node,
        string $word
    ): bool {
        return
            !$node->isLeaf()
            && strlen($node->getLabel()) === strlen($word)
            && strpos(
                $node->getLabel(),
                $word
            ) !== false
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
