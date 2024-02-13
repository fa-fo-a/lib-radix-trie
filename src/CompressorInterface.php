<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

interface CompressorInterface
{
    public function compress(Node $node): string;

    public function uncompress(string $compressed): Node;
}
