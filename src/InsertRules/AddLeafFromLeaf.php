<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\Edge;

class AddLeafFromLeaf extends BaseRule
{
    public function supports(
        Node $node,
        string $word
    ): bool {
        return
            $node->isLeaf()
            && strlen($node->getLabel()) !== strlen($word)
            && strpos(
                $node->getLabel(),
                $word
            ) === false
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

        $this->preserveLeaf(
            $node
        );
    }

    private function preserveLeaf(
        Node $baseNode
    ): void {
        $node = new Node($baseNode->getLabel());
        $edge = new Edge(
            "",
            $node
        );

        $baseNode->addEdge($edge);
    }
}
