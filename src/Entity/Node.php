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

    public function isLeaf(): bool
    {
        return $this->edges === [];
    }

    public function removeEdge(Edge $edge): void
    {
        $key = array_search($edge, $this->edges);
        if ($key === false) {
            return;
        }
        unset($this->edges[$key]);
        $this->edges = array_values($this->edges);
    }

    public function getEdgeToLeaf(): ?Edge
    {
        foreach ($this->getEdges() as $edge) {
            if (strlen($edge->getLabel()) === 0) {
                return $edge;
            }
        }

        return null;
    }
}
