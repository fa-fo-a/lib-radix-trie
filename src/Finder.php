<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

class Finder
{
    private NodeSearcher $nodeSearcher;

    public function __construct()
    {
        $this->nodeSearcher = new NodeSearcher();
    }
    /**
     * @return string[]
     */
    public function find(
        Node $rootNode,
        string $query
    ): array {
        $lookupNode = $this->nodeSearcher->search(
            $rootNode,
            $query
        );

        return array_filter(
            $this->getLeafValuesByQuery(
                $lookupNode,
                $query
            ),
            function (string $value): bool {
                if ($value === '') {
                    return false;
                }

                return true;
            }
        );
    }

    private function getLeafValuesByQuery(
        Node $node,
        string $query
    ): array {
        $output = [];
        if (
            $node->isValue()
            && strpos(
                $node->getLabel(),
                $query
            ) === 0
        ) {
            $output[] = $node->getLabel();
        }
        foreach ($node->getEdges() as $edge => $targetNode) {
            if (strpos(
                $targetNode->getLabel(),
                $query
            ) !== 0) {
                continue;
            }

            $output = array_merge(
                $output,
                $this->getLeafValuesByQuery(
                    $targetNode,
                    $query
                )
            );
        }

        return $output;
    }
}
