<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Entity;

use achertovsky\RadixTrie\Entity\Node;

/** @todo review removal of this entity */
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

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getTargetNode(): Node
    {
        return $this->targetNode;
    }

    public function setTargetNode(Node $node): self
    {
        $this->targetNode = $node;

        return $this;
    }
}
