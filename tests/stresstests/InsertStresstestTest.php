<?php

declare(strict_types=1);

namespace fafoa\RadixTrie\Tests\Stresstest;

use fafoa\RadixTrie\Finder;
use fafoa\RadixTrie\Inserter;
use fafoa\RadixTrie\Entity\Node;
use fafoa\RadixTrie\Tests\BaseTestCase;

class InsertStresstestTest extends BaseTestCase
{
    private const ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST = 2000;

    public function testStresstest(): void
    {
        $inserter = new Inserter();
        $finder = new Finder();
        $rootNode = new Node(Node::ROOT_LABEL);

        $words = [];
        for ($i = 0; $i < self::ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST; $i++) {
            $words[] = $word = $this->randomString(
                mt_rand(
                    2,
                    10
                )
            );
            $inserter->insert(
                $rootNode,
                $word
            );
        }

        $this->assertArraysHaveEqualDataset(
            array_unique($words),
            $finder->find(
                $rootNode,
                ''
            )
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
