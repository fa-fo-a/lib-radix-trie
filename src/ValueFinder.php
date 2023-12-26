<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class ValueFinder
{
    private NodeSearcher $nodeSearcher;
    private StringHelper $stringHelper;

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
            return $output;
        }

        return array_filter(
            $this->getLeafValuesForPrefix(
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
                $this->stringHelper->getCommonPrefixLength(
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
}
