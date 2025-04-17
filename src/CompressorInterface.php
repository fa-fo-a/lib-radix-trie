<?php

declare(strict_types=1);

namespace fafoa\RadixTrie;

use fafoa\RadixTrie\Entity\Node;

interface CompressorInterface
{
    public function compress(Node $node): string;

    public function uncompress(string $compressed): Node;
}
