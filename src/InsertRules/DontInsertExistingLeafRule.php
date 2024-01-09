<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;

class DontInsertExistingLeafRule extends BaseRule
{
    public function supports(
        Node $node,
        string $word
    ): bool {
        return
            $node->isLeaf()
            && strlen($node->getLabel()) === strlen($word)
            && strpos(
                $node->getLabel(),
                $word
            ) === 0
        ;
    }

    public function apply(
        Node $node,
        string $word
    ): void {
    }
}
