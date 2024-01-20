<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\InsertMetadata;
use achertovsky\RadixTrie\StringHelper;

abstract class BaseRule
{
    protected StringHelper $stringHelper;

    public function __construct()
    {
        $this->stringHelper = new StringHelper();
    }

    abstract public function supports(
        InsertMetadata $metadata
    ): bool;

    abstract public function apply(
        Node $node,
        string $word
    ): void;

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
}
