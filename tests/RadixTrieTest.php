<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Edge;

class RadixTrieTest extends TestCase
{
    private const ARBITRARY_AMOUNT_OF_WORDS_FOR_STRESSTEST = 20;

    public function testSearchWorksGood(): void
    {
        // '' => (test) => test => (er) => tester
        //                      => () => test
        $rootNode = new Node(Node::ROOT_LABEL);
        $trie = new RadixTrie(
            $rootNode
        );
        $testLeaf = new Node('test');
        $testLeafsEdge = new Edge('', $testLeaf);
        $testerLeaf = new Node('tester');
        $testerLeafsEdge = new Edge('er', $testerLeaf);
        $testNode = new Node('test');
        $testEdge = new Edge('test', $testNode);
        $testNode->addEdge($testLeafsEdge);
        $testNode->addEdge($testerLeafsEdge);
        $rootNode->addEdge($testEdge);

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $trie->find('t'),
            'finds value for just t, so all leafs'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $trie->find('test'),
            'finds leafs by test query'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'tester',
            ],
            $trie->find('tester'),
            'finds single leaf by tester query'
        );
    }

    private function assertArraysHaveEqualDataset(
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

    public function testSingleValueInsertWouldInsert(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $trie->insert('test');
        $this->assertArraysHaveEqualDataset(
            [
                'test',
            ],
            $trie->find('t'),
            'finds value added'
        );

        $trie->insert('test');
        $this->assertArraysHaveEqualDataset(
            [
                'test',
            ],
            $trie->find('t'),
            'adding same word ruined nothing'
        );
    }

    public function testBuildTrieWithSameWordRoot(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $trie->insert('test');
        $trie->insert('tester');
        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $trie->find('t'),
            'finds all leafs'
        );

        $trie->insert('test');
        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $trie->find('t'),
            'wont duplicate'
        );

        $trie->insert('testing');
        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
                'testing',
            ],
            $trie->find('t'),
            'finds all leafs'
        );
    }

    public function testSameWordRootButLongerWordWontBeFound(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $trie->insert('test');
        $trie->insert('tester');
        $this->assertArraysHaveEqualDataset(
            [],
            $trie->find('testing'),
        );
    }

    public function testShouldReturnWordsWithOnlyRelevantPrefixes(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $trie->insert('tool');
        $trie->insert('rat');

        $this->assertArraysHaveEqualDataset(
            [
                'tool',
            ],
            $trie->find('t'),
            'finds only t starting'
        );
    }

    public function testAddingNodeWordWouldBeAddedAsLeaf(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $trie->insert('tester');
        $trie->insert('testing');
        $trie->insert('test');

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
                'testing',
            ],
            $trie->find('test')
        );
    }

    public function testBuildTrieWithSamePrefixes(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $trie->insert('tester');
        $trie->insert('test');
        $trie->insert('tool');
        $trie->insert('to');

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
                'tool',
                'to',
            ],
            $trie->find(''),
            'finds all leafs'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $trie->find('test'),
            'finds all test leafs'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'tool',
            ],
            $trie->find('tool'),
            'finds tool leaf'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'to',
                'tool',
            ],
            $trie->find('to'),
            'finds tool leaf'
        );
    }

    public function testPartialSearch(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $trie->insert('tester');
        $trie->insert('test');
        $trie->insert('to');

        $this->assertArraysHaveEqualDataset(
            [
                'tester',
            ],
            $trie->find('teste'),
            'finds tester partial edge'
        );
    }

    public function testEmptyTryFindWouldReturnNone(): void
    {
        $trie = new RadixTrie(
            new Node(Node::ROOT_LABEL)
        );

        $this->assertEquals(
            [],
            $trie->find('')
        );

        $this->assertEquals(
            [],
            $trie->find('whatever')
        );
    }

    /** @todo get it out as another testfile */
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
}
