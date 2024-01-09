<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Entity;

use achertovsky\RadixTrie\Entity\Edge;

class Node
{
    public const ROOT_LABEL = '';

    /**
     * @var Edge[]
     */
    private array $edges;

    public function __construct(
        private string $label
    ) {
        /** @todo get rid of array */
        $this->edges = [];
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function addEdge(Edge $edge): self
    {
        $this->edges[] = $edge;

        return $this;
    }

    /**
     * @return Edge[]
     */
    public function getEdges(): array
    {
        return $this->edges;
    }

    // @todo add isNode flag and check by that
    public function isLeaf(): bool
    {
        return $this->edges === [];
    }
}
