<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\BreakRuleMetadata;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\InsertMetadata;

class BreakNodeInsertRule extends BaseRule
{
    public function supports(
        InsertMetadata $metadata
    ): bool {
        return
            !$metadata->isLeaf()
        ;
    }

    public function apply(
        Node $node,
        string $word
    ): void {
        $partialEdgeResult = $this->getPartialMatchingEdge(
            $node,
            $word
        );

        if ($partialEdgeResult !== null) {
            $node = $this->divideEdge(
                $node,
                $partialEdgeResult,
                $word
            );
        }

        $this->addNewEdge(
            $node,
            $word
        );
    }

    private function divideEdge(
        Node $baseNode,
        BreakRuleMetadata $partialEdgeResult
    ): Node {
        $partialEdge = $partialEdgeResult->getEdge();
        $leftLabel = substr($partialEdge->getLabel(), 0, $partialEdgeResult->getLength());
        $rightLabel = substr($partialEdge->getLabel(), $partialEdgeResult->getLength());

        $newNode = new Node($baseNode->getLabel() . $leftLabel);
        $newNode->addEdge(
            new Edge(
                $rightLabel,
                $partialEdgeResult->getEdge()->getTargetNode()
            )
        );
        $partialEdge->setLabel(
            $leftLabel
        );
        $partialEdge->setTargetNode($newNode);

        return $newNode;
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
            if (
                $matchingAmount > 0
                && $matchingAmount < strlen($edge->getLabel())
            ) {
                return new BreakRuleMetadata(
                    $edge,
                    $matchingAmount
                );
            }
        }

        return null;
    }
}
