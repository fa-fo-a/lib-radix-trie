<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Entity;

use achertovsky\RadixTrie\Entity\Node;

class Edge
{
    public function __construct(
        private string $label,
        private Node $targetNode
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTargetNode(): Node
    {
        return $this->targetNode;
    }
}
