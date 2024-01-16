<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

use achertovsky\RadixTrie\Entity\InsertMetadata;
use achertovsky\RadixTrie\Entity\Node;
use achertovsky\RadixTrie\InsertRules\BaseRule;
use achertovsky\RadixTrie\InsertRules\AddLeafFromLeafRule;
use achertovsky\RadixTrie\InsertRules\BreakNodeInsertRule;
use achertovsky\RadixTrie\InsertRules\DontInsertExistingLeafRule;
use achertovsky\RadixTrie\InsertRules\MatchingNodeAndMatchingLeafRule;
use achertovsky\RadixTrie\InsertRules\AddLeafFromNodeWithSameLabelRule;

class Inserter
{
    private NodeSearcher $nodeSearcher;
    private array $rules;

    public function __construct(
    ) {
        $this->nodeSearcher = new NodeSearcher();
        //@todo: add easiest checks at beginning
        $this->rules = [
            new AddLeafFromLeafRule(),
            new AddLeafFromNodeWithSameLabelRule(),
            new DontInsertExistingLeafRule(),
            new MatchingNodeAndMatchingLeafRule(),
            new BreakNodeInsertRule(),
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

        $insertMetadata = new InsertMetadata(
            /** @todo why with metadata it works slower than without? probably creating instances of closure weights */
            fn () => $this->rules[0]->hasSameLabelLeaf($closestNode),
            $closestNode->isLeaf(),
            (new StringHelper())->isSameWords(
                $closestNode->getLabel(),
                $word
            )
        );

        /**
         * @var BaseRule $rule
         */
        foreach ($this->rules as $rule) {
            if (!$rule->supports(
                $insertMetadata
            )) {
                continue;
            }

            $rule->apply(
                $closestNode,
                $word
            );

            return;
        }
    }
}
