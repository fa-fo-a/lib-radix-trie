<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class ValueFinder
{
    private NodeSearcher $nodeSearcher;
    private StringHelper $stringHelper;// @todo not used

    public function __construct()
    {
        $this->nodeSearcher = new NodeSearcher();
        $this->stringHelper = new StringHelper();
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
        if ($lookupNode === null) {
            return $output; //@todo not covered
        }

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
