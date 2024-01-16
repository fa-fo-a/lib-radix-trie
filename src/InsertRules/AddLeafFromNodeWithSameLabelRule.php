<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\InsertMetadata;
use achertovsky\RadixTrie\Entity\Node;

class AddLeafFromNodeWithSameLabelRule extends BaseRule
{
    public function supports(
        InsertMetadata $metadata
    ): bool {
        return
            !$metadata->isLeaf()
            && $metadata->isSameWord()
            && !$metadata->isHasLeafForWord()
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
