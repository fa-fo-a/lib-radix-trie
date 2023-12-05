<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Edge;

/** @todo add test that find with empty string will return none */
class RadixTrieTest extends TestCase
{
    public function testSearchWorksGood(): void
    {
        // '' => (test) => test => (er) => tester
        //                      => () => test
        $rootNode = new Node('');
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
        asort($expectedArray);
        asort($actualArray);

        $this->assertEquals(
            $expectedArray,
            $actualArray,
            $explanation
        );
    }

    public function testSingleValueInsertWouldInsert(): void
    {
        $trie = new RadixTrie(
            new Node('')
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
            new Node('')
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
            new Node('')
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
            new Node('')
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

    public function testBuildTrieWithSamePrefixes(): void
    {
        $trie = new RadixTrie(
            new Node('')
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
            $trie->find('t'),
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
}
