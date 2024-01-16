<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

class RadixTrie
{
    private Inserter $inserter;
    private ValueFinder $valueFinder;

    public function __construct(
        private Node $rootNode = new Node(Node::ROOT_LABEL)
    ) {
        $this->inserter = new Inserter();
        $this->valueFinder = new ValueFinder();
    }

    /**
     * @return string[]
     */
    public function find(string $query): array
    {
        return $this->valueFinder->getLeafValues(
            $this->rootNode,
            $query
        );
    }

    public function insert(string $word): void
    {
        $this->inserter->insert(
            $this->rootNode,
            $word
        );
    }
}
