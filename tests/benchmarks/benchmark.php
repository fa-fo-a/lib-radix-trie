<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';

use fafoa\RadixTrie\Finder;
use fafoa\RadixTrie\Inserter;
use fafoa\RadixTrie\Compressor;
use fafoa\RadixTrie\Entity\Node;

$words = unserialize(
    file_get_contents(
        __DIR__ . '/30k_words.txt'
    )
);
$finder = new Finder();
$inserter = new Inserter();
$start_memory = memory_get_usage();
$rootNode = new Node(Node::ROOT_LABEL);
$compressor = new Compressor();

$insertStart = microtime(true);
foreach ($words as $word) {
    $inserter->insert(
        $rootNode,
        $word
    );
}
$insertEnd = microtime(true);

echo sprintf(
    "Inserted in total %s unique words\n",
    count(array_unique($words))
);

$findAllStart = microtime(true);
$finder->find(
    $rootNode,
    ''
);
$findAllEnd = microtime(true);

$findOneWordAllStart = microtime(true);
$finder->find(
    $rootNode,
    'momzuwap'
);
$findOneWordAllEnd = microtime(true);
gc_collect_cycles();
echo sprintf(
    'Memory consumed %s Kb',
    (memory_get_usage() - $start_memory)/1024
)."\n";

$serializeStart = microtime(true);
$compressedData = $compressor->compress($rootNode);
$serializeEnd = microtime(true);


$deserializeStart = microtime(true);
$compressor->uncompress($compressedData);
$deserializeEnd = microtime(true);

$addSingleStart = microtime(true);
$inserter->insert(
    $rootNode,
    'mombuwap'
);
$addSingleEnd = microtime(true);

echo sprintf(
    "Insert takes %s (s.ms)\nSearch all words takes %s (s.ms)\nSearch single word takes %s (s.ms)\n"
    . "Compression %s (s.ms)\n"
    . "Decompression %s (s.ms)\n"
    . "Insert single word takes %s (s.ms)\n",
    sprintf("%.020f", $insertEnd - $insertStart),
    sprintf("%.020f", $findAllEnd - $findAllStart),
    sprintf("%.020f", $findOneWordAllEnd - $findOneWordAllStart),
    sprintf("%.020f", $serializeEnd - $serializeStart),
    sprintf("%.020f", $deserializeEnd - $deserializeStart),
    sprintf("%.020f", $addSingleEnd - $addSingleStart),
)."\n";
