<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie;

class StringHelper
{
    public function getCommonPrefixLength(
        string $line1,
        string $line2
    ): int {
        $maxLength = min(
            strlen($line1),
            strlen($line2)
        );

        $arrayedLine1 = str_split($line1);
        $arrayedLine2 = str_split($line2);
        for ($i = 0; $i < $maxLength; $i++) {
            if ($arrayedLine1[$i] !== $arrayedLine2[$i]) {
                return $i;
            }
        }

        return $maxLength;
    }

    public function getSuffix(string $prefix, string $haystack): string
    {
        if (strlen($prefix) === 0) {
            return $haystack;
        }

        return substr($haystack, strlen($prefix));
    }
}
