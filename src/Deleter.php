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
        // '' => (test) => 'test'
        $partialWord = substr($word, 0, -1);
        $closestNode = $this->nodeSearcher->search(
            $rootNode,
            $partialWord
        );
        // get partial matching edge
        // check if found edge matches searched word
        // remove edge from closesNode if target Node is leaf

        //@todo: no pointer to prior node
    }
}
