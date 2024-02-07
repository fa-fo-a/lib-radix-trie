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
        $expectedRootNode = (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                new Node('test', true)
            )
        ;

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
        $expectedRootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                new Node('test', true)
            )
        ;

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
        $expectedRootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test', true))
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
                ->addLeaf(
                    'ing',
                    new Node('testing', true)
                )
            )
        ;

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
        $expectedRootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                't',
                (new Node('t'))
                ->addLeaf(
                    'o',
                    (new Node('to', true))
                    ->addLeaf(
                        'ol',
                        new Node('tool', true)
                    )
                )
                ->addLeaf(
                    'est',
                    (new Node('test', true))
                    ->addLeaf(
                        'er',
                        new Node('tester', true)
                    )
                )
            )
        ;

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
