<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Entity;

use Closure;

class InsertMetadata
{
    public function __construct(
        private Closure $hasLeafForWordCallback,
        private bool $leaf = false,
        private bool $sameWord = false,
        private ?bool $hasLeafForWord = null
    ) {
    }

    public function isLeaf(): bool
    {
        return $this->leaf;
    }

    public function isSameWord(): bool
    {
        return $this->sameWord;
    }

    public function isHasLeafForWord(): bool
    {
        if ($this->hasLeafForWord === null) {
            $this->hasLeafForWord = call_user_func($this->hasLeafForWordCallback);
        }

        return $this->hasLeafForWord;
    }
}
