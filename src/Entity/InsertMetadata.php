<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Entity;

use Closure;

class InsertMetadata
{
    private ?Node $node = null;

    public function __construct(
        private Closure $hasLeafForWordCallback,
        private bool $leaf = false,
        private bool $sameWord = false,
        private ?bool $hasLeafForWord = null
    ) {
    }

    public function setNode(Node $node): self
    {
        $this->node = $node;

        return $this;
    }

    public function setLeaf(bool $leaf): self
    {
        $this->leaf = $leaf;

        return $this;
    }

    public function isLeaf(): bool
    {
        return $this->leaf;
    }

    public function isSameWord(): bool
    {
        return $this->sameWord;
    }

    public function setSameWord(bool $sameWord): self
    {
        $this->sameWord = $sameWord;

        return $this;
    }

    public function clean(): void
    {
        $this->hasLeafForWord = null;
    }

    public function isHasLeafForWord(): bool
    {
        if ($this->hasLeafForWord === null) {
            $this->hasLeafForWord = $this->hasLeafForWordCallback->call($this, $this->node);
        }

        return $this->hasLeafForWord;
    }
}
