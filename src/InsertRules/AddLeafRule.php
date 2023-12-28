<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\InsertRules;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\Entity\Edge;

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
