<?php

namespace achertovsky\RadixTrie;

class StringHelper
{
    public function getAmountOfMatchingSymbols(
        string $line1,
        string $line2
    ): int {
        $arrayedLine1 = str_split($line1);
        $arrayedLine2 = str_split($line2);

        return count(
            array_intersect_assoc(
                $arrayedLine1,
                $arrayedLine2
            )
        );
    }

    public function getSuffix(string $prefix, string $haystack): string
    {
        if ($prefix === '') {
            return $haystack;
        }

        return explode($prefix, $haystack, 2)[1];
    }
}
