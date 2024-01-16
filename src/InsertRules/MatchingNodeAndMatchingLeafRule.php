<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\InsertMetadata;

class MatchingNodeAndMatchingLeafRule extends BaseRule
{
    public function supports(
        InsertMetadata $metadata
    ): bool {
        return
            !$metadata->isLeaf()
            && $metadata->isSameWord()
            && $metadata->isHasLeafForWord()
        ;
    }

    public function apply(
        Node $node,
        string $word
    ): void {
    }
}
