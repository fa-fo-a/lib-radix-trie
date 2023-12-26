<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Edge;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\InsertRules\AddLeafRule;
use achertovsky\RadixTrie\InsertRules\BaseRule;
use achertovsky\RadixTrie\InsertRules\GrowLeafFromNonRootLeafRule;
use achertovsky\RadixTrie\InsertRules\MatchingLeafRule;
use achertovsky\RadixTrie\InsertRules\MatchingNodeAndMatchingLeafRule;
use achertovsky\RadixTrie\InsertRules\MatchingNodeAndMissingLeafRule;

class Inserter
{
    private StringHelper $stringHelper;
    private NodeSearcher $nodeSearcher;
    private array $rules;

    public function __construct(
    ) {
        $this->stringHelper = new StringHelper();
        $this->nodeSearcher = new NodeSearcher();
        //@todo: add easiest checks at beginning
        $this->rules = [
            new MatchingLeafRule(),
            new MatchingNodeAndMissingLeafRule(),
            new MatchingNodeAndMatchingLeafRule(),
            new GrowLeafFromNonRootLeafRule(),
            new AddLeafRule(),
        ];
    }

    public function insert(
        Node $rootNode,
        string $word
    ): void {
        $closestNode = $this->nodeSearcher->search(
            $rootNode,
            $word
        );

        /**
         * @var BaseRule $rule
         */
        foreach ($this->rules as $rule) {
            if (!$rule->supports(
                $closestNode,
                $word
            )) {
                continue;
            }

            $rule->apply(
                $closestNode,
                $word
            );

            if ($rule->isFinal()) {
                return;
            }
        }

        // if (
        //     $closestNode->getLabel() === $word
        //     && $closestNode->isLeaf()
        // ) {
        //     return;
        // }

        // if (
        //     $closestNode->getLabel() === $word
        //     && !$closestNode->isLeaf()
        //     && !$this->hasSameLabelLeaf($closestNode)
        // ) {
        //     $this->addNewEdge(
        //         $closestNode,
        //         $closestNode->getLabel()
        //     );

        //     return;
        // }

        // if (
        //     $closestNode->getLabel() === $word
        //     && !$closestNode->isLeaf()
        //     && $this->hasSameLabelLeaf($closestNode)
        // ) {
        //     return;
        // }

        // if (
        //     $closestNode->isLeaf()
        //     && !$closestNode->isRoot()
        // ) {
        //     $this->addNewEdge(
        //         $closestNode,
        //         $closestNode->getLabel()
        //     );
        // }

        // $partialEdge = $this->getPartialMatchingEdge(
        //     $closestNode,
        //     $word
        // );
        // if ($partialEdge !== null) {
        //     $closestNode = $this->divideEdge(
        //         $closestNode,
        //         $partialEdge,
        //         $word
        //     );
        // }

        // $this->addNewEdge(
        //     $closestNode,
        //     $word
        // );
    }
}
