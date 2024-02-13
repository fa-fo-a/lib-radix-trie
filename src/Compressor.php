<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

class Compressor
{
    public function __construct(
        private string $fqcnReplacer = ''
    ) {
    }

    public function compress(Node $node): string
    {
        return gzcompress(
            serialize($node)
        );
    }

    public function uncompress(string $compressed): Node
    {
        return unserialize(
            gzuncompress($compressed)
        );
    }
}
