<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Support;

/**
 * Trait SpecSupport
 *
 * @property  string $specDirectory
 */
trait SpecSupport
{
    /**
     * @return string
     */
    private function getSpecDirectory(): string
    {
        if (\property_exists($this, 'specDirectory')) {
            return $this->specDirectory;
        }

        return __DIR__ . '/../.resources';
    }

    /**
     * @return array
     */
    public function specProvider(): array
    {
        $files = \iterator_to_array($this->finds());
        \sort($files, SORT_STRING);

        $tests = [];

        foreach ($files as $test) {
            $spec = new SpecTest($test->getRealPath());

            foreach ($this->diffs($spec) as $diff) {
                if (\is_file($diff)) {
                    @\unlink($diff);
                }
            }

            $tests[] = [$spec];
        }

        return $tests;
    }

    /**
     * @return \RegexIterator
     */
    private function finds(): \RegexIterator
    {
        $files = new \RecursiveDirectoryIterator($this->getSpecDirectory());
        $files = new \RecursiveIteratorIterator($files);

        return new \RegexIterator($files, '/\.phpt$/', \RegexIterator::MATCH);
    }

    /**
     * @param string $path
     * @return SpecTest
     */
    protected function spec(string $path): SpecTest
    {
        return new SpecTest($this->getSpecDirectory() . '/' . $path);
    }

    /**
     * @param SpecTest $spec
     * @param string $actual
     * @return string
     */
    private function specDiff(SpecTest $spec, string $actual): string
    {
        if (0 === \stripos(PHP_OS, 'WIN')) {
            //$this->markTestIncomplete('Windows OS probably does not support "diff" command');

            return '';
        }

        [$fileExpected, $fileActual, $fileDiff] = $this->diffs($spec);

        $cmd = 'diff --strip-trailing-cr --label "%s" --label "%s" --unified "%s" "%s"';
        $cmd = \sprintf($cmd, 'expect', 'out', $fileExpected, $fileActual);

        @\file_put_contents($fileExpected, $spec->getOut() . PHP_EOL);
        @\file_put_contents($fileActual, $actual . PHP_EOL);

        \exec($cmd, $out);

        $result = \implode(PHP_EOL, (array)$out);

        @\file_put_contents($fileDiff, $result);

        return $result;
    }

    /**
     * @param SpecTest $spec
     * @return array
     */
    private function diffs(SpecTest $spec): array
    {
        return [
            $spec->getPath() . '.exp',
            $spec->getPath() . '.out',
            $spec->getPath() . '.diff',
        ];
    }
}
