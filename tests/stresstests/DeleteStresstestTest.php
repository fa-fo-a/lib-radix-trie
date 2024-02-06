<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests\Stresstest;

use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Tests\BaseTestCase;

class DeleteStresstestTest extends BaseTestCase
{
    private const ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST = 150;
    private const HOW_MANY_WORDS_REMOVE = 15;

    public function testStresstest(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );
        for ($i = 0; $i < self::ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST; $i++) {
            $word = $this->randomString(
                mt_rand(
                    2,
                    10
                )
            );
            $trie->insert(
                $word
            );
        }

        while (1) {
            $expectedTrie = new RadixTrie(
                new Node(Node::ROOT_LABEL)
            );
            $wordsToRemove = [];
            $words = $trie->find('');
            if ($words === []) {
                break;
            }
            for ($i = 0; $i < self::HOW_MANY_WORDS_REMOVE; $i++) {
                $word = $words[array_rand($words)];
                $wordsToRemove[] = $word;
                $trie->delete($word);
            }
            foreach ($words as $word) {
                if (in_array($word, $wordsToRemove)) {
                    continue;
                }
                $expectedTrie->insert($word);
            }

            $this->assertNodeRecursivelyEqual(
                $expectedTrie->getRootNode(),
                $trie->getRootNode()
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
