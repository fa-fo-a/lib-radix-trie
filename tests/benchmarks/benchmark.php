<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';

use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Node;

$words = unserialize(
    file_get_contents(
        __DIR__ . '/30k_words.txt'
    )
);

$trie = new RadixTrie(
    new Node(Node::ROOT_LABEL)
);

$insertStart = microtime(true);
foreach ($words as $word) {
    $trie->insert($word);
}
$insertEnd = microtime(true);

echo sprintf(
    "Inserted in total %s unique words\n",
    count(array_unique($words))
);

$findAllStart = microtime(true);
$trie->find('');
$findAllEnd = microtime(true);

$findOneWordAllStart = microtime(true);
$trie->find('momzuwap');
$findOneWordAllEnd = microtime(true);


echo sprintf(
    "Insert takes %s (s.ms)\nSearch all words takes %s (s.ms)\nSearch single word takes %s (s.ms)",
    sprintf("%.020f", $insertEnd - $insertStart),
    sprintf("%.020f", $findAllEnd - $findAllStart),
    sprintf("%.020f", $findOneWordAllEnd - $findOneWordAllStart),
)."\n";
