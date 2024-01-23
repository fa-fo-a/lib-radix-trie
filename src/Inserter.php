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
    private InsertMetadata $insertMetadata;
    private StringHelper $stringHelper;

    public function __construct(
    ) {
        $this->nodeSearcher = new NodeSearcher();
        $this->rules = [
            new AddLeafFromLeafRule(),
            new AddLeafFromNodeWithSameLabelRule(),
            new DontInsertExistingLeafRule(),
            new MatchingNodeAndMatchingLeafRule(),
            new BreakNodeInsertRule(),
        ];
        $this->insertMetadata = new InsertMetadata(
            function (Node $node) {
                return $node->getEdgeToLeaf() !== null;
            },
            false,
            false,
            null
        );
        $this->stringHelper = new StringHelper();
    }

    public function insert(
        Node $rootNode,
        string $word
    ): void {
        $closestNode = $this->nodeSearcher->search(
            $rootNode,
            $word
        );

        $this->insertMetadata->clean();
        $this->insertMetadata
            ->setNode($closestNode)
            ->setLeaf($closestNode->isLeaf())
            ->setSameWord(
                $this->stringHelper
                    ->isSameWords(
                        $closestNode->getLabel(),
                        $word
                    )
            )
        ;

        /**
         * @var BaseRule $rule
         */
        foreach ($this->rules as $rule) {
            if (!$rule->supports(
                $this->insertMetadata
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
