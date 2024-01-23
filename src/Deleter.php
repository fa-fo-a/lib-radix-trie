<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class Deleter
{
    private NodeSearcher $nodeSearcher;

    public function __construct()
    {
        $this->nodeSearcher = new NodeSearcher();
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
            return;
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
        if ($edgeToWorkOn->getTargetNode()->isLeaf()) {
            $closestNode->removeEdge($edgeToWorkOn);
            //check if closest node has only one edge which leaf
            if ($closestNode->getEdgeToLeaf() && count($closestNode->getEdges()) === 1) {
                $closestNode->removeEdge($closestNode->getEdgeToLeaf());
            }

            if (!$closestNode->getEdgeToLeaf() && count($closestNode->getEdges()) === 1) {
                $leftoverNode = reset($closestNode->getEdges())->getTargetNode();
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
                        $leftoverNode->getLabel(),
                        $leftoverNode
                    )
                );
                // @todo it could be broken if we wont work from root node. just copy-paste test and add "t" word into it
            }

            return;
        }

        // if edge is intermediary node check if it has edge to leaf
        $edgeToLeaf = $edgeToWorkOn->getTargetNode()->getEdgeToLeaf();
        if ($edgeToLeaf !== null) {
            $edgeToWorkOn->getTargetNode()->removeEdge($edgeToLeaf);
            return;
        }

        // // if intermediary node has only leaf - remove leaf
        // if (count($edgeToWorkOn->getTargetNode()->getEdges()) === 1) {
        //     $closestNode->removeEdge($edgeToWorkOn);
        //     return;
        // }

        // get partial matching edge
        // check if found edge matches searched word
        // remove edge from closesNode if target Node is leaf

        //@todo: no pointer to prior node
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
