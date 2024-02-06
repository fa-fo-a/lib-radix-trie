<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use achertovsky\RadixTrie\Finder;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Tests\BaseTestCase;

class FinderTest extends BaseTestCase
{
    private Finder $finder;
    private Node $rootNode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new Finder();

        $this->rootNode = new Node(Node::ROOT_LABEL);
        $testNode = $this->addLeafToNode(
            $this->rootNode,
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
            $this->rootNode,
            'rat',
            'rat'
        );
    }

    public function testSearchWorksGood(): void
    {
        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
                'rat',
            ],
            $this->finder->find(
                $this->rootNode,
                ''
            ),
            'finds all leafs'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $this->finder->find(
                $this->rootNode,
                't'
            ),
            'finds value for just t, so all leafs'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'test',
                'tester',
            ],
            $this->finder->find(
                $this->rootNode,
                'test'
            ),
            'finds leafs by test query'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'tester',
            ],
            $this->finder->find(
                $this->rootNode,
                'tester'
            ),
            'finds single leaf by tester query'
        );

        $this->assertArraysHaveEqualDataset(
            [],
            $this->finder->find(
                $this->rootNode,
                'testing'
            ),
            'wont find same root but longer word'
        );

        $this->assertArraysHaveEqualDataset(
            [
                'rat',
            ],
            $this->finder->find(
                $this->rootNode,
                'r'
            ),
            'finds another by partial query'
        );
        $this->assertArraysHaveEqualDataset(
            [
                'rat',
            ],
            $this->finder->find(
                $this->rootNode,
                'rat'
            ),
            'finds another branch by full word'
        );
    }

    public function testEmptyTryFindWouldReturnNone(): void
    {
        $rootNode = new Node(Node::ROOT_LABEL);

        $this->assertEquals(
            [],
            $this->finder->find(
                $rootNode,
                ''
            ),
            'wont take root node as a result'
        );

        $this->assertEquals(
            [],
            $this->finder->find(
                $rootNode,
                'whatever'
            )
        );
    }
}
