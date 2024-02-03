<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\BreakRuleMetadata;

class Inserter
{
    private NodeSearcher $nodeSearcher;
    private StringHelper $stringHelper;

    public function __construct(
    ) {
        $this->nodeSearcher = new NodeSearcher();
        $this->stringHelper = new StringHelper();
    }

    public function insert(
        Node $rootNode,
        string $word
    ): void {
        $closestNode = $this->nodeSearcher->search(
            $rootNode,
            $word
        );

        $isLeaf = $closestNode->isLeaf();
        $isSameWord = $this->stringHelper->isSameWords(
            $closestNode->getLabel(),
            $word
        );

        if ($isLeaf) {
            if ($isSameWord) {
                return;
            }

            $this->preserveLeafForNonRootNode($closestNode);

            $this->addLeafToNode(
                $closestNode,
                $word
            );

            return;
        }

        if (
            $isSameWord
            && $closestNode->getEdgeToLeaf()
        ) {
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
        string $targetNodeLabel,
        ?string $edgeLabel = null
    ): void {
        $targetNode = new Node($targetNodeLabel);
        $edge = new Edge(
            $edgeLabel
            ?? $this->stringHelper->getSuffix(
                $sourceNode->getLabel(),
                $targetNodeLabel
            ),
            $targetNode
        );
        $sourceNode->addEdge($edge);
    }

    protected function getPartialMatchingEdge(
        Node $baseNode,
        string $word
    ): ?BreakRuleMetadata {
        $suffix = $this->stringHelper->getSuffix(
            $baseNode->getLabel(),
            $word
        );
        foreach ($baseNode->getEdges() as $edge) {
            $matchingAmount = $this->stringHelper->getCommonPrefixLength(
                $edge->getLabel(),
                $suffix
            );
            if ($matchingAmount > 0) {
                return new BreakRuleMetadata(
                    $edge,
                    $matchingAmount
                );
            }
        }

        return null;
    }

    private function preserveLeafForNonRootNode(
        Node $node
    ): void {
        if (strlen($node->getLabel()) === 0) {
            return;
        }
        $this->addLeafToNode($node, $node->getLabel(), '');
    }

    private function createJunctionNode(
        Node $closestNode,
        BreakRuleMetadata $breakRuleMetadata
    ): Node {
        $partialEdge = $breakRuleMetadata->getEdge();
        $leftLabel = substr(
            $partialEdge->getLabel(),
            0,
            $breakRuleMetadata->getLength()
        );
        $rightLabel = substr(
            $partialEdge->getLabel(),
            $breakRuleMetadata->getLength()
        );

        $newNode = new Node($closestNode->getLabel() . $leftLabel);
        $newNode->addEdge(
            new Edge(
                $rightLabel,
                $partialEdge->getTargetNode()
            )
        );
        $partialEdge->setLabel(
            $leftLabel
        );
        $partialEdge->setTargetNode($newNode);

        return $newNode;
    }
}
