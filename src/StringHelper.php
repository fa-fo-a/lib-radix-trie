<?php

declare(strict_types=1);

namespace fafoa\RadixTrie;

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

        for ($i = 0; $i < $maxLength; $i++) {
            if ($line1[$i] !== $line2[$i]) {
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

    public function isSameWords(
        string $firstWord,
        string $secondWord
    ): bool {
        return strlen($firstWord) === strlen($secondWord)
            && strpos(
                $firstWord,
                $secondWord
            ) === 0
        ;
    }
}
