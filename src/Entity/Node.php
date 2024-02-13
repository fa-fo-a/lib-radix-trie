<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Entity;

class Node
{
    public const ROOT_LABEL = '';

    public function __construct(
        protected string $label,
        protected bool $value = false,
        protected array $edges = []
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function addLeaf(string $label, Node $node): self
    {
        $this->edges[$label] = $node;

        return $this;
    }

    public function getEdges(): array
    {
        return $this->edges;
    }

    public function isLeaf(): bool
    {
        return $this->edges === [];
    }

    public function isValue(): bool
    {
        return $this->value;
    }

    public function setIsValue(bool $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function removeEdge(string $label): void
    {
        unset($this->edges[$label]);
    }
}
