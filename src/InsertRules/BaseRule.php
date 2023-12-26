<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\StringHelper;

abstract class BaseRule
{
    protected StringHelper $stringHelper;

    public function __construct()
    {
        $this->stringHelper = new StringHelper();
    }

    abstract public function supports(
        Node $node,
        string $word
    ): bool;

    abstract public function apply(
        Node $node,
        string $word
    ): void;

    // @todo: should be removed, because only a single rule should apply
    abstract public function isFinal(): bool;

    // @todo: consider misplaced responsibility
    protected function addNewEdge(
        Node $baseNode,
        string $word
    ): void {
        $node = new Node($word);
        $edge = new Edge(
            $this->stringHelper->getSuffix(
                $baseNode->getLabel(),
                $word
            ),
            $node
        );

        $baseNode->addEdge($edge);
    }

    // @todo: consider misplaced responsibility
    protected function hasSameLabelLeaf(
        Node $node
    ): bool {
        foreach ($node->getEdges() as $edge) {
            if ($node->getLabel() === $edge->getTargetNode()->getLabel()) {
                return true;
            }
        }

        return false;
    }
}
