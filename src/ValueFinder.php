<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

class ValueFinder
{
    private NodeSearcher $nodeSearcher;

    public function __construct()
    {
        $this->nodeSearcher = new NodeSearcher();
    }
    /**
     * @return string[]
     */
    public function getLeafValues(
        Node $rootNode,
        string $query
    ): array {
        $output = [];

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
        if ($node->isLeaf()) {
            return [$node->getLabel()];
        }

        $output = [];
        foreach ($node->getEdges() as $edge) {
            if (strpos(
                $edge->getTargetNode()->getLabel(),
                $query
            ) !== 0) {
                continue;
            }

            $output = array_merge(
                $output,
                $this->getLeafValuesByQuery(
                    $edge->getTargetNode(),
                    $query
                )
            );
        }

        return $output;
    }
}
