<?php

declare(strict_types=1);

namespace fafoa\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;
use fafoa\RadixTrie\Compressor;
use fafoa\RadixTrie\Entity\Node;

class CompressorTest extends TestCase
{
    public function testSerializerWontShorten(): void
    {
        $compressor = new Compressor();
        $node = new Node(Node::ROOT_LABEL);

        $this->assertEquals(
            $node,
            $compressor->uncompress(
                $compressor->compress($node)
            )
        );
    }
}
