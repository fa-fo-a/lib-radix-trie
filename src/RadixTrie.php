<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;

class RadixTrie
{
    private Inserter $inserter;
    private ValueFinder $valueFinder;
    private Deleter $deleter;

    public function __construct(
        private Node $rootNode = new Node(Node::ROOT_LABEL)
    ) {
        $this->inserter = new Inserter();
        $this->valueFinder = new ValueFinder();
        $this->deleter = new Deleter();
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

    public function delete(string $word): void
    {
        $arr = $this->valueFinder->getLeafValues(
            $this->rootNode,
            ''
        );
        $pos = array_search($word, $arr);
        if ($pos === false) {
            return;
        }
        unset($arr[$pos]);

        // @todo: mwahahaha
        $this->rootNode = new Node(Node::ROOT_LABEL);
        foreach ($arr as $word) {
            $this->insert($word);
        }
    }

    public function getRootNode(): Node
    {
        return $this->rootNode;
    }
}
