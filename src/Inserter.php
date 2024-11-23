<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\BreakRuleMetadata;

class Inserter
{
    public function __construct(
        private NodeSearcher $nodeSearcher = new NodeSearcher(),
        private StringHelper $stringHelper = new StringHelper()
    ) {
    }

    public function insert(
        Node $rootNode,
        string $word
    ): void {
        $closestNode = $this->nodeSearcher->search(
            $rootNode,
            $word
        );

        $isValue = $closestNode->isValue();
        $isSameWord = $this->stringHelper->isSameWords(
            $closestNode->getLabel(),
            $word
        );

        if ($isValue && $isSameWord) {
            return;
        }
        if (!$isValue && $isSameWord) {
            if (strlen($word) > 0) {
                $closestNode->setIsValue(true);
            }

            return;
        }

        $isLeaf = $closestNode->isLeaf();
        if ($isLeaf) {
            $this->addLeafToNode(
                $closestNode,
                $word
            );

            return;
        }

        $breakRuleMetadata = $this->getPartialMatchingEdge(
            $closestNode,
            $word
        );
        if ($breakRuleMetadata === null) {
            $this->addLeafToNode(
                $closestNode,
                $word
            );

            return;
        }

        $junctionNode = $this->createJunctionNode(
            $closestNode,
            $breakRuleMetadata
        );

        $this->addLeafToNode(
            $junctionNode,
            $word
        );
    }

    private function addLeafToNode(
        Node $sourceNode,
        string $targetNodeLabel
    ): void {
        if ($sourceNode->getLabel() === $targetNodeLabel) {
            $sourceNode->setIsValue(true);

            return;
        }
        $sourceNode->addLeaf(
            $this->stringHelper->getSuffix(
                $sourceNode->getLabel(),
                $targetNodeLabel
            ),
            new Node($targetNodeLabel, true)
        );
    }

    protected function getPartialMatchingEdge(
        Node $baseNode,
        string $word
    ): ?BreakRuleMetadata {
        $suffix = $this->stringHelper->getSuffix(
            $baseNode->getLabel(),
            $word
        );
        foreach ($baseNode->getEdges() as $edge => $node) {
            $matchingAmount = $this->stringHelper->getCommonPrefixLength(
                $edge,
                $suffix
            );
            if ($matchingAmount > 0) {
                return new BreakRuleMetadata(
                    $edge,
                    $matchingAmount,
                    $suffix
                );
            }
        }

        return null;
    }

    private function createJunctionNode(
        Node $closestNode,
        BreakRuleMetadata $breakRuleMetadata
    ): Node {
        $partialEdge = $breakRuleMetadata->getEdge();
        $leftLabel = substr(
            $partialEdge,
            0,
            $breakRuleMetadata->getLength()
        );
        $rightLabel = substr(
            $partialEdge,
            $breakRuleMetadata->getLength()
        );

        $newNode = new Node($closestNode->getLabel() . $leftLabel);
        $newNode->addLeaf(
            $rightLabel,
            $closestNode->getEdges()[$partialEdge]
        );
        $closestNode->removeEdge($partialEdge);
        $closestNode->addLeaf(
            $leftLabel,
            $newNode
        );

        return $newNode;
    }
}
