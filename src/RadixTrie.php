<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class RadixTrie
{
    public function __construct(
        private Node $rootNode = new Node('')
    ) {
    }

    /**
     * @return string[]
     */
    // public function find(string $query): array
    // {
    //     $output = [];
    //     $lookupNode = $this->lookup($query);
    //     if ($lookupNode === null) {
    //         return $output;
    //     }
    //     return $this->getLeafValues($lookupNode);
    // }

    // private function getLeafValues(Node $node): array
    // {
    //     if ($node->isLeaf()) {
    //         return [$node->getLabel()];
    //     }
    //     $output = [];
    //     foreach ($node->getEdges() as $edge) {
    //         $output = array_merge($this->getLeafValues($edge->getTargetNode()), $output);
    //     }

    //     return $output;
    // }

    public function insert(string $word): void
    {
        //@todo: edge case when node already exists with empty leaf value
        $lookupNode = $this->lookup($word, false);

        if (null === $lookupNode) {
            $this->rootNode = new Node(
                $word
            );

            return;
        }

        // test + tester
        // test -> () -> test
        //      -> ('er') -> tester
        // comon = 'test'
        // old = ''
        // new 'er'

        $commonPrefix = $this->getCommonPrefix(
            $lookupNode->getLabel(),
            $word
        );
        $commonNode = new Node(
            $commonPrefix
        );
        $commonEdge


        $oldLeftoverSuffix = $this->getSuffix($commonPrefix, $lookupNode->getLabel());
        $newLeftoverSuffix = $this->getSuffix($commonPrefix, $word);

        $newCommonEdge = new Edge(
            $commonPrefix
        );




        $leftEdge = new Edge(
            $oldLeftoverSuffix,
            null
        );
        $leftEdge->addEdges($lookupNode->getEdges());
        $lookupNode->setEdges(
            [
                $newCommonEdge,
            ]
        );

        // if (
        //     str_starts_with(
        //         $word,
        //         $lookupNode->getLabel()
        //     )
        // )
        // $newNode = new Node($word);
        // $newEdge = new Edge(
        //     $this->getSuffix(
        //         $lookupNode->getLabel(),
        //         $word
        //     ),
        //     $newNode
        // );
        // $lookupNode->addEdge($newEdge);

        // $newLeaf = new Node($lookupNode->getLabel());
        // $newLeafEdge = new Edge(
        //     '',
        //     $newLeaf
        // );
        // $lookupNode->addEdge($newLeafEdge);

//        $closestEdge = $lookupNode->getMatchingEdge($word, false);
//        $matchingLabel = 'o';
//        $leftOverLabel = 'st';
//        $insertedLabel = 'ast';
//        $newNode = new Node($lookupNode->getLabel() . $matchingLabel);
//        $newEdge = new Edge($leftOverLabel);

        // define left, define right


//        $lookupNode->insert($word);

//        public function insert(string $word): void
//    {
//
//        word: toad
//        t
//        (oast)
//            toad - t  = oad
//
//    }

        // @todo next part is find mutual suffix between addAfterNode label and word we adding and rebuild the trie
        // $addAfterNode->addEdge(
        //     new Edge(
        //         $addAfterNode->getLabel(),

        //     )
        // )
    }

    private function getCommonPrefix(string $text1, string $text2): string
    {
        $array1 = str_split($text1);
        $array2 = str_split($text2);
        $intersect = array_intersect_assoc($array1, $array2);

        return implode('', $intersect);
    }

    private function getSuffix(string $prefix, string $haystack): string
    {
        return explode($prefix, $haystack, 2)[1];
    }


//    t -> (oast) -> toast -> (er) -> toaster
//    t(oad)

    /**
     * @todo it should return array
     */
    private function lookup(string $query, bool $exactMatch = true): ?Node
    {
        if ($this->rootNode === null) {
            return null;
        }
        $currentNode = $this->rootNode;
        // $currentEdgeLength = 0;

        // while ($currentNode !== null && !$currentNode->isLeaf() && $currentEdgeLength < strlen($query)) {
        while ($currentNode !== null && !$currentNode->isLeaf() && $currentNode->getLabel() !== $query) {
            $edge = $currentNode->getMatchingEdge($query);
            if ($edge === null) {
                return $exactMatch ? null : $currentNode;
            }

            $currentNode = $edge->getTargetNode(); // @todo maybe do not expose node internals here?
            // $currentEdgeLength = strlen($edge->getLabel());
        }

        return $currentNode;
    }
}
