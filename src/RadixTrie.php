<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

// @todo question the SOLIDity of solution (as we create instances in constructor)
// @todo radixtrie is redundant
class RadixTrie
{
    private Inserter $inserter;
    private Finder $valueFinder;
    private Deleter $deleter;

    public function __construct(
        private Node $rootNode = new Node(Node::ROOT_LABEL)
    ) {
        $this->inserter = new Inserter();
        $this->valueFinder = new Finder();
        $this->deleter = new Deleter();
    }

    /**
     * @return string[]
     */
    public function find(string $query): array
    {
        return $this->valueFinder->find(
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

    public function delete(string $word): void
    {
        $this->deleter->delete(
            $this->rootNode,
            $word
        );
    }

    public function getRootNode(): Node
    {
        return $this->rootNode;
    }
}
