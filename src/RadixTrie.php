<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class RadixTrie
{
    private StringHelper $stringHelper;

    public function __construct(
        private Node $rootNode = new Node(Node::ROOT_LABEL)
    ) {
        $this->stringHelper = new StringHelper();
    }

    /**
     * @return string[]
     */
    public function find(string $query): array
    {
        $output = [];
        $lookupNode = $this->lookup($query);
        if ($lookupNode === null) {
            return $output;
        }

        return $this->getLeafValuesForPrefix(
            $lookupNode,
            $query
        );
    }

    private function lookup(string $query): ?Node
    {
        $currentNode = $this->rootNode;
        $currentEdgeLength = 0;

        while (
            $currentNode !== null
            && !$currentNode->isLeaf()
            && $currentEdgeLength < strlen($query)
        ) {
            $edge = $this->getMatchingEdge(
                $currentNode,
                $query
            );
            if ($edge === null) {
                return $currentNode;
            }

            $currentNode = $edge->getTargetNode();
            $currentEdgeLength = strlen($edge->getLabel());
        }

        return $currentNode;
    }

    private function getMatchingEdge(
        Node $node,
        string $query
    ): ?Edge {
        $leftover = $this->stringHelper->getSuffix(
            $node->getLabel(),
            $query
        );
        foreach ($node->getEdges() as $edge) {
            $matchingAmount = $this->stringHelper->getAmountOfMatchingSymbols(
                $leftover,
                $edge->getLabel()
            );
            if (
                $matchingAmount > 0
                && $matchingAmount === strlen($edge->getLabel())
            ) {
                return $edge;
            }
        }

        return null;
    }

    private function getLeafValuesForPrefix(
        Node $node,
        string $prefix
    ): array {
        if ($node->isLeaf()) {
            return [$node->getLabel()];
        }

        $output = [];
        foreach ($node->getEdges() as $edge) {
            if (
                $this->stringHelper->getAmountOfMatchingSymbols(
                    $edge->getTargetNode()->getLabel(),
                    $prefix
                ) < strlen($prefix)
            ) {
                continue;
            }

            $output = array_merge(
                $output,
                $this->getLeafValuesForPrefix(
                    $edge->getTargetNode(),
                    $prefix
                )
            );
        }

        return $output;
    }

    public function insert(string $word): void
    {
        $closestNode = $this->lookup($word);

        if (
            $closestNode->getLabel() === $word
            && $closestNode->isLeaf()
        ) {
            return;
        }

        if (
            $closestNode->getLabel() === $word
            && !$closestNode->isLeaf()
            && !$this->hasSameLabelLeaf($closestNode)
        ) {
            $this->addNewEdge(
                $closestNode,
                $closestNode->getLabel()
            );

            return;
        }

        if (
            $closestNode->isLeaf()
            && !$closestNode->isRoot()
        ) {
            $this->addNewEdge(
                $closestNode,
                $closestNode->getLabel()
            );
        }

        $partialEdge = $this->getPartialMatchingEdge(
            $closestNode,
            $word
        );
        if ($partialEdge !== null) {
            $closestNode = $this->divideEdge(
                $closestNode,
                $partialEdge,
                $word
            );
        }

        $this->addNewEdge(
            $closestNode,
            $word
        );
    }

    private function divideEdge(
        Node $baseNode,
        Edge $partialEdge,
        string $word
    ): Node {
        // @todo: fix test when adding node with same label without empty edge
        $mutualPrefix = $this->stringHelper->getMutualPrefix(
            $partialEdge->getTargetNode()->getLabel(),
            $word
        );
        $newNode = new Node($mutualPrefix);
        $newNode->addEdge(
            new Edge(
                $this->stringHelper->getSuffix(
                    $mutualPrefix,
                    $partialEdge->getTargetNode()->getLabel()
                ),
                $partialEdge->getTargetNode()
            )
        );
        $partialEdge->setLabel(
            $this->stringHelper->getSuffix(
                $baseNode->getLabel(),
                $mutualPrefix
            )
        );
        $partialEdge->setTargetNode($newNode);

        return $newNode;
    }

    private function getPartialMatchingEdge(
        Node $baseNode,
        string $word
    ): ?Edge {
        $suffix = $this->stringHelper->getSuffix(
            $baseNode->getLabel(),
            $word
        );
        foreach ($baseNode->getEdges() as $edge) {
            $matchingAmount = $this->stringHelper->getAmountOfMatchingSymbols(
                $edge->getLabel(),
                $suffix
            );
            if (
                $matchingAmount > 0
                && $matchingAmount < strlen($edge->getLabel())
            ) {
                return $edge;
            }
        }

        return null;
    }

    private function addNewEdge(
        Node $baseNode,
        string $word
    ): void {
        $node = new Node($word);
        $edge = new Edge(
            $this->stringHelper->getSuffix(
                $baseNode->getLabel(),
                $word
            ),
            $node
        );

        $baseNode->addEdge($edge);
    }

    private function hasSameLabelLeaf(
        Node $node
    ): bool {
        foreach ($node->getEdges() as $edge) {
            if ($node->getLabel() === $edge->getTargetNode()->getLabel()) {
                return true;
            }
        }

        return false;
    }
}
