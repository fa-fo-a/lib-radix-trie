<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

// @todo pretty that ugly bastard
class Deleter
{
    private NodeSearcher $nodeSearcher;
    private StringHelper $stringHelper;

    public function __construct()
    {
        $this->nodeSearcher = new NodeSearcher();
        $this->stringHelper = new StringHelper();
    }

    public function delete(
        Node $rootNode,
        string $word
    ): void {
        $closestNode = $this->findClosestNode(
            $rootNode,
            $word
        );

        if ($closestNode === null) {
            return; // @todo uncovered
        }

        $edgeToWorkOn = null;
        foreach ($closestNode->getEdges() as $edge) {
            if (
                $edge->getTargetNode()->getLabel() === $word
            ) {
                $edgeToWorkOn = $edge;
                break;
            }
        }
        if ($edgeToWorkOn === null) {
            return;
        }

        // if edge we found targets node which is leaf - just remove it
        //        $trie->insert('t');
        //        $trie->insert('testing');
        //        $trie->insert('tester');
        // '' => (t) => t => (est) => test => (ing) => testing
        if ($edgeToWorkOn->getTargetNode()->isLeaf()) {
            $closestNode->removeEdge($edgeToWorkOn);

            // check if closest node has only one edge which is not leaf
            $this->getRidOfUnnecessaryNode(
                $rootNode,
                $closestNode
            );

            return;
        }

        // if edge is intermediary node check if it has edge to leaf
        $edgeToLeaf = $edgeToWorkOn->getTargetNode()->getEdgeToLeaf();
        if ($edgeToLeaf !== null) {
            $edgeToWorkOn->getTargetNode()->removeEdge($edgeToLeaf);

            $this->getRidOfUnnecessaryNode(
                $rootNode,
                $edgeToWorkOn->getTargetNode()
            );

            return;
        }
    }

    private function getRidOfUnnecessaryNode(
        Node $rootNode,
        Node $closestNode
    ): void {
        if ($closestNode === $rootNode) {
            return;
        }

        if (count($closestNode->getEdges()) === 1) {
            $edges = $closestNode->getEdges();
            $leftoverNode = reset($edges)->getTargetNode();
            $closestNodeToClosestNode = $this->findClosestNode(
                $rootNode,
                $closestNode->getLabel()
            );
            foreach ($closestNodeToClosestNode->getEdges() as $edge) {
                if ($edge->getTargetNode() === $closestNode) {
                    $closestNodeToClosestNode->removeEdge($edge);
                    break;
                }
            }
            $closestNodeToClosestNode->addEdge(
                new Edge(
                    $this->stringHelper->getSuffix($closestNodeToClosestNode->getLabel(), $leftoverNode->getLabel()),
                    $leftoverNode
                )
            );
        }
    }

    private function findClosestNode(
        Node $rootNode,
        string $word
    ): ?Node {
        $partialWord = substr(
            $word,
            0,
            -1
        );
        return $this->nodeSearcher->search(
            $rootNode,
            $partialWord
        );
    }
}
