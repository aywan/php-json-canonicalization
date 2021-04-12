<?php

declare(strict_types=1);

namespace tests;

use aywan\JsonCanonicalization\JsonCanonicalizationFactory;
use aywan\JsonCanonicalization\Canonicalizator;
use aywan\JsonCanonicalization\Utils;
use PHPUnit\Framework\TestCase;

class NumberFormatTest extends TestCase
{
    public function testOk(): void
    {
        foreach ($this->provideTestData() as [$number, $expected]) {

            $unpacked = unpack('E', hex2bin($number))[1];

            $actual = Utils::es6NumberFormat($unpacked);
            self::assertEquals($expected, $actual);
        }

    }

    public function provideTestData(): \Generator
    {
        $filename = TEST_BASE_DIR . '/testdata/es6testfile100m.txt';

        if (! file_exists($filename) || ! is_readable($filename)) {

            yield ['4340000000000001', '9007199254740994'];
            yield ['4340000000000002', '9007199254740996'];
            yield ['444b1ae4d6e2ef50', '1e+21'];
            yield ['3eb0c6f7a0b5ed8d', '0.000001'];
            yield ['3eb0c6f7a0b5ed8c', '9.999999999999997e-7'];
            yield ['8000000000000000', '0'];
            yield ['0000000000000000', '0'];

            return;
        }

        $f = fopen($filename, 'rb');
        while ($line = fgets($f)) {
            [$n, $e] = explode(',', $line);

            $n = trim($n);
            $n = str_pad($n, 16, '0', \STR_PAD_LEFT);

            $e = trim($e);

            yield [trim($n), trim($e)];
        }
    }
}