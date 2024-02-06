<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Inserter;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Tests\BaseTestCase;

class InserterTest extends BaseTestCase
{
    private Inserter $inserter;

    protected function setUp(): void
    {
        $this->inserter = new Inserter();
    }

    public function testSingleValueInsertWouldInsert(): void
    {
        $expectedRootNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $expectedRootNode,
            'test',
            'test'
        );

        $rootNode = new Node(Node::ROOT_LABEL);

        $this->inserter->insert(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedRootNode,
            $rootNode
        );
    }

    public function testSingleValueInsertWouldNotDuplicate(): void
    {
        $expectedRootNode = new Node(Node::ROOT_LABEL);
        $this->addLeafToNode(
            $expectedRootNode,
            'test',
            'test'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $this->inserter->insert(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedRootNode,
            $rootNode
        );

        $this->inserter->insert(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedRootNode,
            $rootNode
        );
    }

    /**
     * @dataProvider dataBuildTrieWithSameWordRoot
     */
    public function testBuildTrieWithSameWordRootAndAddingOrderWontMatter(
        array $differentlyOrderedWords
    ): void {
        $expectedRootNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $expectedRootNode,
            'test',
            'test'
        );
        $this->addLeafToNode(
            $testNode,
            'test',
            ''
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );
        $this->addLeafToNode(
            $testNode,
            'testing',
            'ing'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        foreach ($differentlyOrderedWords as $word) {
            $this->inserter->insert(
                $rootNode,
                $word
            );
        }

        $this->assertNodeRecursivelyEqual(
            $expectedRootNode,
            $rootNode
        );
    }

    public static function dataBuildTrieWithSameWordRoot(): array
    {
        return [
            [
                [
                    'test',
                    'tester',
                    'testing',
                ],
            ],
            [
                [
                    'tester',
                    'test',
                    'testing',
                ],
            ],
            [
                [
                    'testing',
                    'tester',
                    'test',
                ],
            ],
        ];
    }

    public function testBuildTrieWithSamePrefixes(): void
    {
        $expectedRootNode = new Node(Node::ROOT_LABEL);
        $tNode = $this->addLeafToNode(
            $expectedRootNode,
            't',
            't'
        );
        $toNode = $this->addLeafToNode(
            $tNode,
            'to',
            'o'
        );
        $this->addLeafToNode(
            $toNode,
            'to',
            ''
        );
        $this->addLeafToNode(
            $toNode,
            'tool',
            'ol'
        );
        $testNode = $this->addLeafToNode(
            $tNode,
            'test',
            'est'
        );
        $this->addLeafToNode(
            $testNode,
            'test',
            ''
        );
        $this->addLeafToNode(
            $testNode,
            'tester',
            'er'
        );

        $rootNode = new Node(Node::ROOT_LABEL);
        $this->inserter->insert(
            $rootNode,
            'to'
        );
        $this->inserter->insert(
            $rootNode,
            'test'
        );
        $this->inserter->insert(
            $rootNode,
            'tool'
        );
        $this->inserter->insert(
            $rootNode,
            'tester'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedRootNode,
            $rootNode
        );
    }
}
