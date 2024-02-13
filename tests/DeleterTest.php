<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Deleter;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Tests\BaseTestCase;

class DeleterTest extends BaseTestCase
{
    private Deleter $deleter;

    protected function setUp(): void
    {
        $this->deleter = new Deleter();
    }

    public function testWillNotDeleteRootNode(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);

        $this->deleter->delete($rootNode, Node::ROOT_LABEL);
        $this->assertNodeRecursivelyEqual(
            new Node(Node::ROOT_LABEL),
            $rootNode
        );
    }

    public function testWillDeleteLeaf(): void
    {
        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                new Node(
                    'test',
                    true
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            new Node(Node::ROOT_LABEL),
            $rootNode
        );
    }

    public function testWillNotDeleteAsNoMatchingNode(): void
    {
        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                new Node(
                    'test',
                    true
                )
            )
        ;

        $expectedNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                new Node(
                    'test',
                    true
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            't'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWontDeleteIntermediaryNode(): void
    {
        $expectedNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test'))
                ->addLeaf(
                    'ing',
                    new Node('testing', true)
                )
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
            )
        ;

        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test'))
                ->addLeaf(
                    'ing',
                    new Node('testing', true)
                )
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteIntermediaryNodeValue(): void
    {
        $expectedNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test'))
                ->addLeaf(
                    'ing',
                    new Node('testing', true)
                )
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
            )
        ;

        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test', true))
                ->addLeaf(
                    'ing',
                    new Node('testing', true)
                )
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            'test'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteLeafAndTransformIntermediaryNodeToLeaf(): void
    {
        $expectedNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test', true))
            )
        ;

        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test', true))
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            'tester'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteOneOfLeafsAndCollapseIntermediaryNodeWhichGrowsFromRoot(): void
    {
        $expectedNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'testing',
                new Node('testing', true)
            )
        ;

        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test'))
                ->addLeaf(
                    'ing',
                    new Node('testing', true)
                )
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            'tester'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testWillDeleteOneOfLeafsAndCollapseIntermediaryNode(): void
    {
        $expectedNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                't',
                (new Node('t', true))
                ->addLeaf(
                    'esting',
                    new Node('testing', true)
                )
            )
        ;

        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                't',
                (new Node('t', true))
                ->addLeaf(
                    'est',
                    (new Node('test'))
                    ->addLeaf(
                        'ing',
                        new Node('testing', true)
                    )
                    ->addLeaf(
                        'er',
                        new Node('tester', true)
                    )
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            'tester'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testDeleteFirstIntermediaryNode(): void
    {
        $expectedNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                (new Node('test', true))
                ->addLeaf(
                    'ing',
                    new Node('testing', true)
                )
                ->addLeaf(
                    'er',
                    new Node('tester', true)
                )
            )
        ;

        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                't',
                (new Node('t', true))
                ->addLeaf(
                    'est',
                    (new Node('test', true))
                    ->addLeaf(
                        'ing',
                        new Node('testing', true)
                    )
                    ->addLeaf(
                        'er',
                        new Node('tester', true)
                    )
                )
            )
        ;

        $this->deleter->delete(
            $rootNode,
            't'
        );

        $this->assertNodeRecursivelyEqual(
            $expectedNode,
            $rootNode
        );
    }

    public function testDeleteTwoLeafsOfRoot(): void
    {
        $rootNode =
            (new Node(Node::ROOT_LABEL))
            ->addLeaf(
                'test',
                new Node('test', true)
            )
            ->addLeaf(
                'west',
                new Node('west', true)
            )
        ;

        $this->deleter->delete(
            $rootNode,
            'test'
        );
        $this->deleter->delete(
            $rootNode,
            'west'
        );

        $this->assertNodeRecursivelyEqual(
            new Node(Node::ROOT_LABEL),
            $rootNode
        );
    }
}
