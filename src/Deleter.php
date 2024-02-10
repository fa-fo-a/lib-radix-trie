<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

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
        $closestNode = $this->findParentForNodePossiblyContainingWord(
            $rootNode,
            $word
        );

        // test -> (er) -> tester

        $edgeThatTargetsWord = null;
        $edges = $closestNode->getEdges();
        foreach ($edges as $edge => $targetNode) {
            if (strpos(
                    $word,
                    $targetNode->getLabel()
                ) === 0
            ) {
                $edgeThatTargetsWord = $edge;
                break;
            }
        }
        if ($edgeThatTargetsWord === null) {
            return;
        }

        $nodeToWorkOn = null;
        $edgeToRemove = null;
        if ($edges[$edgeThatTargetsWord]->isValue()) {
            if ($edges[$edgeThatTargetsWord]->isLeaf()) {
                $closestNode->removeEdge($edgeThatTargetsWord);
                $nodeToWorkOn = $closestNode;
            } else {
                $edges[$edgeThatTargetsWord]->setValue(false);
                $nodeToWorkOn = $edges[$edgeThatTargetsWord];
            }
        }

        // if ($closestNode->getEdges()[$edgeThatTargetsWord]->isLeaf()) {
        //     $nodeToWorkOn = $closestNode;
        //     $edgeToRemove = $edgeThatTargetsWord;
        // } elseif ($edgeThatTargetsWord->getTargetNode()->getEdgeToLeaf()) {
        //     $nodeToWorkOn = $edgeThatTargetsWord->getTargetNode();
        //     $edgeToRemove = $edgeThatTargetsWord->getTargetNode()->getEdgeToLeaf();
        // }

        if ($nodeToWorkOn === null) {
            return;
        }

        // $nodeToWorkOn->removeEdge($edgeToRemove);

        $this->collapseRedundantNode(
            $rootNode,
            $nodeToWorkOn
        );
    }

    private function findParentForNodePossiblyContainingWord(
        Node $rootNode,
        string $word
    ): Node {
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

    private function collapseRedundantNode(
        Node $rootNode,
        Node $possiblyRedundantNode
    ): void {
        if (
            $possiblyRedundantNode === $rootNode
            || $possiblyRedundantNode->isValue()
        ) {
            return;
        }


        $edges = $possiblyRedundantNode->getEdges();
        if (count($edges) > 1) {
            return;
        }
        $leftoverEdge = key($edges);
        $leftoverNode = $edges[$leftoverEdge];
        $redundantNodeParent = $this->findParentForNodePossiblyContainingWord(
            $rootNode,
            $possiblyRedundantNode->getLabel()
        );

        $this->removeRedundantIntermediaryNode(
            $redundantNodeParent,
            $possiblyRedundantNode
        );

        $this->addLeftoverNodeStraightToParent(
            $redundantNodeParent,
            $leftoverNode
        );
    }

    private function removeRedundantIntermediaryNode(
        Node $redundantNodeParent,
        Node $redundantNode
    ): void {
        foreach ($redundantNodeParent->getEdges() as $edge => $targetNode) {
            if ($targetNode === $redundantNode) {
                $redundantNodeParent->removeEdge($edge);
                break;
            }
        }
    }

    private function addLeftoverNodeStraightToParent(
        Node $redundantNodeParent,
        Node $leftoverNode
    ): void {
        $redundantNodeParent->addLeaf(
            $this->stringHelper->getSuffix(
                $redundantNodeParent->getLabel(),
                $leftoverNode->getLabel()
            ),
            $leftoverNode
        );
    }
}
