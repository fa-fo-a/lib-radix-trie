<?php

declare(strict_types=1);

namespace fafoa\RadixTrie\Tests\Stresstest;

use fafoa\RadixTrie\Finder;
use fafoa\RadixTrie\Deleter;
use fafoa\RadixTrie\Inserter;
use fafoa\RadixTrie\Entity\Node;
use fafoa\RadixTrie\Tests\BaseTestCase;

class DeleteStresstestTest extends BaseTestCase
{
    private const ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST = 150;
    private const HOW_MANY_WORDS_REMOVE = 15;

    public function testStresstest(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);
        $inserter = new Inserter();
        $finder = new Finder();
        $deleter = new Deleter();
        for ($i = 0; $i < self::ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST; $i++) {
            $word = $this->randomString(
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

        while (1) {
            $expectedRootNode = new Node(Node::ROOT_LABEL);
            $wordsToRemove = [];
            $words = $finder->find(
                $rootNode,
                ''
            );
            if ($words === []) {
                break;
            }
            for ($i = 0; $i < self::HOW_MANY_WORDS_REMOVE; $i++) {
                $word = $words[array_rand($words)];
                $wordsToRemove[] = $word;
                $deleter->delete(
                    $rootNode,
                    $word
                );
            }
            foreach ($words as $word) {
                if (in_array($word, $wordsToRemove)) {
                    continue;
                }
                $inserter->insert(
                    $expectedRootNode,
                    $word
                );
            }

            $this->assertNodeRecursivelyEqual(
                $expectedRootNode,
                $rootNode
            );
        }
    }

    private function randomString($length = 6)
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= chr(mt_rand(97, 122));
        }

        return $result;
    }
}
