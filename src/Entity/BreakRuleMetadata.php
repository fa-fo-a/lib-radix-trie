<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Entity;

class BreakRuleMetadata
{
    public function __construct(
        private readonly Edge $edge,
        private readonly int $length
    ) {
    }

    public function getEdge(): Edge
    {
        return $this->edge;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
