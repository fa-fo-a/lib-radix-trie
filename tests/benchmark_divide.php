<?php

declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Node;

$words = [];
for ($c=3000;$c>1;$c--) {
    $words[] = str_pad('', $c, 'a', STR_PAD_LEFT);
}

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

echo sprintf(
    "Insert takes %s (s.ms)\n",
    $insertEnd - $insertStart,
)."\n";
