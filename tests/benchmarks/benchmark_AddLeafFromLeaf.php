<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';

use achertovsky\RadixTrie\RadixTrie;
use achertovsky\RadixTrie\Entity\Node;

$word = 'a';
$trie = new RadixTrie(
    new Node(Node::ROOT_LABEL)
);

$words = [];
for ($c=1;$c<1000;$c++) {
    $word .= 'a';
    $words[] = $word;
}

$insertStart = microtime(true);
foreach ($words as $word) {
    $trie->insert($word);
}
$insertEnd = microtime(true);

echo sprintf(
    "Insert takes %s (s.ms)\n",
    $insertEnd - $insertStart,
)."\n";
