<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;

class RadixTrie
{
    private StringHelper $stringHelper;

    public function __construct(
        private Node $rootNode = new Node('')
    ) {
        $this->stringHelper = new StringHelper();
    }

    /**
     * @return string[]
     */
    public function find(string $query): array
    {
        $output = [];
        $lookupNode = $this->lookup($query);
        if ($lookupNode === null) {
            return $output;
        }

        return $this->getLeafValuesForPrefix(
            $lookupNode,
            $query
        );
    }

    private function lookup(string $query): ?Node
    {
        if ($this->rootNode === null) {
            return null;
        }
        $currentNode = $this->rootNode;
        $currentEdgeLength = 0;

        while (
            $currentNode !== null
            && !$currentNode->isLeaf()
            && $currentEdgeLength < strlen($query)
        ) {
            $edge = $this->getMatchingEdge(
                $currentNode,
                $query
            );
            if ($edge === null) {
                return $currentNode;
            }

            $currentNode = $edge->getTargetNode();
            $currentEdgeLength = strlen($edge->getLabel());
        }

        return $currentNode;
    }

    private function getMatchingEdge(
        Node $node,
        string $query
    ): ?Edge {
        $leftover = str_replace(
            $node->getLabel(),
            '',
            $query
        );
        foreach ($node->getEdges() as $edge) {
            $matchingAmount = $this->stringHelper->getAmountOfMatchingSymbols(
                $leftover,
                $edge->getLabel()
            );
            if (
                $matchingAmount > 0
                && $matchingAmount === strlen($edge->getLabel())
            ) {
                return $edge;
            }
        }

        return null;
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
                $this->stringHelper->getAmountOfMatchingSymbols(
                    $edge->getTargetNode()->getLabel(),
                    $prefix
                ) < strlen($prefix)
            ) {
                // @todo alex make sure that covered by docker run --rm -it --add-host=host.docker.internal:host-gateway -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie php -d pcov.enabled=1  vendor/bin/phpunit --coverage-clover=.vscode/coverage/coverage.xml --filter=testSameWordRootButLongerWordWontBeFound
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

    public function insert(string $word): void
    {
        $closestNode = $this->lookup($word);

        if ($closestNode->getLabel() === $word) {
            return;
        }

        if ($this->ifLeafHaveToBePreserved(
            $closestNode,
            $word
        )) {
            $this->addNewEdge(
                $closestNode,
                $closestNode->getLabel()
            );
        }

        $this->addNewEdge(
            $closestNode,
            $word
        );
    }

    private function ifLeafHaveToBePreserved(
        Node $baseNode,
        string $word
    ): bool {
        $baseNodeLabel = $baseNode->getLabel();

        return $baseNode->isLeaf()
            && $baseNodeLabel !== ''
            && $this->stringHelper->getAmountOfMatchingSymbols(
                $baseNodeLabel,
                $word
            ) === strlen($baseNodeLabel)
        ;
    }

    private function addNewEdge(
        Node $baseNode,
        string $word
    ): void {
        $node = new Node($word);
        $edge = new Edge(
            $this->stringHelper->getSuffix(
                $baseNode->getLabel(),
                $word
            ),
            $node
        );

        $baseNode->addEdge($edge);
    }

    //     //@todo: edge case when node already exists with empty leaf value
    //     $lookupNode = $this->lookup($word, false);

    //     if (null === $lookupNode) {
    //         $this->rootNode = new Node(
    //             $word
    //         );

    //         return;
    //     }

    //     // test + tester
    //     // test -> () -> test
    //     //      -> ('er') -> tester
    //     // comon = 'test'
    //     // old = ''
    //     // new 'er'

    //     $commonPrefix = $this->getCommonPrefix(
    //         $lookupNode->getLabel(),
    //         $word
    //     );
    //     $commonNode = new Node(
    //         $commonPrefix
    //     );

    //     $oldLeftoverSuffix = $this->getSuffix($commonPrefix, $lookupNode->getLabel());
    //     $newLeftoverSuffix = $this->getSuffix($commonPrefix, $word);

    //     $newCommonEdge = new Edge(
    //         $commonPrefix
    //     );




    //     $leftEdge = new Edge(
    //         $oldLeftoverSuffix,
    //         null
    //     );
    //     $leftEdge->addEdges($lookupNode->getEdges());
    //     $lookupNode->setEdges(
    //         [
    //             $newCommonEdge,
    //         ]
    //     );

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
    // }

    private function getCommonPrefix(string $text1, string $text2): string
    {
        $array1 = str_split($text1);
        $array2 = str_split($text2);
        $intersect = array_intersect_assoc($array1, $array2);

        return implode('', $intersect);
    }
}
