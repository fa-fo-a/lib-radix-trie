<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\InsertRules\AddLeafRule;
use achertovsky\RadixTrie\InsertRules\BaseRule;
use achertovsky\RadixTrie\InsertRules\GrowLeafFromNonRootLeafRule;
use achertovsky\RadixTrie\InsertRules\DontInsertExistingRule;
use achertovsky\RadixTrie\InsertRules\MatchingNodeAndMatchingLeafRule;
use achertovsky\RadixTrie\InsertRules\MatchingNodeAndMissingLeafRule;

class Inserter
{
    private NodeSearcher $nodeSearcher;
    private array $rules;

    public function __construct(
    ) {
        $this->nodeSearcher = new NodeSearcher();
        //@todo: add easiest checks at beginning
        $this->rules = [
            new DontInsertExistingRule(),
            new MatchingNodeAndMatchingLeafRule(),
            new MatchingNodeAndMissingLeafRule(),
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
    }
}
