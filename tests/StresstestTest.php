<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Tests\BaseTest;

class StresstestTest extends BaseTest
{
    private const ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST = 2000;

    public function testStresstest(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        $words = [];
        for ($i = 0; $i < self::ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST; $i++) {
            $words[] = $word = $this->randomString(
                mt_rand(
                    2,
                    10
                )
            );
            $trie->insert(
                $word
            );
        }

        $this->assertArraysHaveEqualDataset(
            array_unique($words),
            $trie->find('')
        );
    }

    private function randomString($length = 6)
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= chr(mt_rand(97, 122));
        }

        return $result;
    }

    protected function assertArraysHaveEqualDataset(
        array $expectedArray,
        array $actualArray,
        string $explanation = ''
    ): void {
        sort($expectedArray);
        sort($actualArray);

        $this->assertEquals(
            $expectedArray,
            $actualArray,
            $explanation
        );
    }
}
