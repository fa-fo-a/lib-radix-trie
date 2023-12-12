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

        return max(
            count(
                array_intersect_assoc(
                    $arrayedLine1,
                    $arrayedLine2
                )
            ),
            count(
                array_intersect_assoc(
                    $arrayedLine2,
                    $arrayedLine1
                )
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

    public function getMutualPrefix(string $haystack, string $needle): string
    {
        return substr(
            $haystack,
            0,
            $this->getAmountOfMatchingSymbols(
                $haystack,
                $needle
            )
        );
    }
}
