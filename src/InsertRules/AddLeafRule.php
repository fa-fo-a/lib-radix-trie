<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\StringHelper;

class AddLeafRule extends BaseRule
{
    public function supports(
        Node $node,
        string $word
    ): bool {
        return true;
    }

    public function apply(
        Node $node,
        string $word
    ): void {
        $partialEdge = $this->getPartialMatchingEdge(
            $node,
            $word
        );
        if ($partialEdge !== null) {
            $node = $this->divideEdge(
                $node,
                $partialEdge,
                $word
            );
        }

        $this->addNewEdge(
            $node,
            $word
        );
    }

    public function isFinal(): bool
    {
        return false;
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
            $matchingAmount = $this->stringHelper->getCommonPrefixLength(
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


    private function divideEdge(
        Node $baseNode,
        Edge $partialEdge,
        string $word
    ): Node {
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
}
