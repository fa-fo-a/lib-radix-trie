<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

class Deleter
{
    private NodeSearcher $nodeSearcher;

    public function __construct()
    {
        $this->nodeSearcher = new NodeSearcher();
    }

    public function delete(
        Node $rootNode,
        string $word
    ): void
    {
        $closestNode = $this->nodeSearcher->search(
            $rootNode,
            $word
        );

        //@todo: no pointer to prior node
    }
}
