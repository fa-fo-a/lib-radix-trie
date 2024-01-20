<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Tests\BaseTestCase;

class RadixTrieSearchTest extends BaseTestCase
{
    private RadixTrie $trie;

    protected function setUp(): void
    {
        parent::setUp();

        $rootNode = new Node(Node::ROOT_LABEL);
        $this->trie = new RadixTrie(
            $rootNode
        );

        // '' => (test) => test => (er) => tester
        //                      => () => test
        $testLeaf = new Node('test');
        $testLeafsEdge = new Edge('', $testLeaf);
        $testerLeaf = new Node('tester');
        $testerLeafsEdge = new Edge('er', $testerLeaf);
        $testNode = new Node('test');
        $testEdge = new Edge('test', $testNode);
        $testNode->addEdge($testLeafsEdge);
        $testNode->addEdge($testerLeafsEdge);
        $rootNode->addEdge($testEdge);
    }

    public function testSearchWorksGood(): void
    {
        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $this->trie->find('t'),
            'finds value for just t, so all leafs'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $this->trie->find('test'),
            'finds leafs by test query'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'tester',
            ],
            $this->trie->find('tester'),
            'finds single leaf by tester query'
        );
    }

    public function testPartialSearch(): void
    {
        $this->assertArraysHaveEqualDataset(
            [
                'tester',
            ],
            $this->trie->find('teste'),
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
}
