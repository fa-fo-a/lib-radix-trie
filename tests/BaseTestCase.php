<?php

declare(strict_types=1);

namespace achertovsky\RadixTrie\Tests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function assertArraysHaveEqualDataset(
        array $expectedArray,
        array $actualArray,
        string $explanation = ''
    ): void {
        sort($expectedArray);
        sort($actualArray);

        $this->assertEquals(
            $expectedArray,
            $actualArray,
            $explanation
        );
    }
}
