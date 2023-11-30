<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\RadixTrie;

class RadixTrieTest extends TestCase
{
    private RadixTrie $trie;

    protected function setUp(): void
    {
        $rootNode = new Node('');

        $testerNode = new Node('tester');
        $testerEdge = new Edge('er', $testerNode);
        $testEmptyNode = new Node('test');
        $testEmptyEdge = new Edge('', $testEmptyNode);
        $testNode = new Node('test');
        $testEdge = new Edge('test', $testNode);
        $testNode->addEdge($testEmptyEdge);
        $testNode->addEdge($testerEdge);

        $rootNode->addEdge($testEdge);

        // @todo: make search
        // '' => (test) => test => (er) => tester
        //                      => () => test
        $this->trie = new RadixTrie(
            $rootNode
        );
    }

    public function test(): void
    {
        $this->trie->insert('test');
        // $this->trie->insert('tester');
        $this->trie->insert('toaster');
//        $this->trie->insert('toasting');
//        $this->trie->insert('slow');
//        $this->trie->insert('slowly');

        $this->assertEquals(
            [
                'test',
                'tester',
            ],
            $this->trie->find('test')
        );

//        $this->assertEquals(
//            [
//                'test',
//                'toaster',
//                'toasting',
//            ],
//            $this->trie->find('t')
//        );

//        $this->assertEquals(
//            [
//                'slow',
//                'slowly',
//            ],
//            $this->trie->find('s')
//        );
//
//        $this->assertEquals(
//            [
//                'toaster',
//                'toasting',
//            ],
//            $this->trie->find('to')
//        );
//
//        $this->assertEquals(
//            [
//                'toaster',
//            ],
//            $this->trie->find('toaster')
//        );
    }
}
