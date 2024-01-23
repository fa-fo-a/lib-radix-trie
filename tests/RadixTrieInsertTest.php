<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Tests\BaseTestCase;

/** @todo test why trie with added 1 word has leaf with empty edge and node */
class RadixTrieInsertTest extends BaseTestCase
{
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

}
