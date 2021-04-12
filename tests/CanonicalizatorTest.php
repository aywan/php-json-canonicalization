<?php

declare(strict_types=1);

namespace tests;

use aywan\JsonCanonicalization\JsonCanonicalizationFactory;
use aywan\JsonCanonicalization\Canonicalizator;
use PHPUnit\Framework\TestCase;

class CanonicalizatorTest extends TestCase
{
    /**
     * @param string $case
     *
     * @dataProvider provideTestData
     */
    public function testOk(string $case): void
    {
        $input = $this->getInputForCase($case);
        $outHex = $this->getOutHexForCase($case);
        $output = $this->getOutputForCase($case);

        $canonicalization = JsonCanonicalizationFactory::getInstance();

        $result = $canonicalization->canonicalize($input);
        self::assertEquals($output, $result);

        $hex = $canonicalization->canonicalize($input, true);
        self::assertEquals($outHex, $hex);
    }

    public function provideTestData(): array
    {
        return [
            ['arrays'],
            ['french'],
            ['structures'],
            ['unicode'],
            ['values'],
            ['weird'],
        ];
    }

    private function getInputForCase(string $case)
    {
        return json_decode($this->getFileData(TEST_BASE_DIR . '/testdata/input/' . $case . '.json'));
    }

    private function getOutHexForCase(string $case): string
    {
        return rtrim(str_replace("\n", ' ', $this->getFileData(TEST_BASE_DIR . '/testdata/outhex/' . $case . '.txt')));
    }

    private function getOutputForCase(string $case): string
    {
        return $this->getFileData(TEST_BASE_DIR . '/testdata/output/' . $case . '.json');
    }

    /**
     * @param string $path
     *
     * @throws \Exception
     * @return string
     */
    private function getFileData(string $path): string
    {
        if (! file_exists($path)) {
            throw new \Exception("$path is not exists");
        }
        if (! is_readable($path)) {
            throw new \Exception("$path is not readable");
        }
        return file_get_contents($path);
    }
}