<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

class RadixTrie
{
    public function __construct(
        private ?Node $rootNode = null
    ) {
    }

    /**
     * @return string[]
     */
    public function find(string $query): array
    {
        return [];
    }

    public function insert(string $word): void
    {
        $addAfterNode = $this->lookup($word);

        if (null === $addAfterNode) {
            $this->rootNode = new Node(
                $word
            );

            return;
        }

        // @todo next part is find mutual suffix between addAfterNode label and word we adding and rebuild the trie
        // $addAfterNode->addEdge(
        //     new Edge(
        //         $addAfterNode->getLabel(),

        //     )
        // )
    }

    /**
     * @todo it should return array
     */
    private function lookup(string $query): ?Node
    {
        if ($this->rootNode === null) {
            return null;
        }
        $currentNode = $this->rootNode;
        // $currentEdgeLength = 0;

        // while ($currentNode !== null && !$currentNode->isLeaf() && $currentEdgeLength < strlen($query)) {
        while ($currentNode !== null && !$currentNode->isLeaf()) {
            $edge = $currentNode->getMatchingEdge($query);
            if ($edge === null) {
                return null;
            }

            $currentNode = $edge->getTargetNode(); // @todo maybe do not expose node internals here?
            // $currentEdgeLength = strlen($edge->getLabel());
        }

        return $currentNode;
    }
}
