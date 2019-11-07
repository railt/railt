<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Unit;

use Railt\SDL\Parser;
use Phplrt\Source\File;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Source\Exception\NotFoundException;
use PHPUnit\Framework\ExpectationFailedException;
use Phplrt\Source\Exception\NotReadableException;

/**
 * Class DumpsTestCase
 */
class FullDumpsTestCase extends TestCase
{
    /**
     * @var Parser
     */
    private Parser $parser;

    /**
     * @return void
     * @throws \Throwable
     */
    public function setUp(): void
    {
        $this->parser = new Parser();
    }

    /**
     * @return array
     * @throws NotFoundException
     * @throws NotReadableException
     */
    public function schemas(): array
    {
        $result = [];

        foreach (\glob(__DIR__ . '/resources/*.graphql') as $file) {
            $result[\basename($file)] = [File::fromPathname($file)];
        }

        return $result;
    }

    /**
     * @dataProvider schemas
     *
     * @param FileInterface $file
     * @return void
     * @throws ExpectationFailedException
     * @throws \Throwable
     */
    public function testDumps(FileInterface $file): void
    {
        $directory = \dirname($file->getPathname());

        $output = $directory . '/' . \basename($file->getPathname(), '.graphql') . '.out.json';

        $ast = $this->parser->parse($file);

        if (\is_file($output)) {
            // \unlink($output);
        }

        if (! \is_file($output)) {
            $this->write($output, $this->encode($ast));
        }

        $this->assertSame($this->decode($this->read($output)), $this->format($ast));
    }

    /**
     * @param string $pathname
     * @return string
     */
    private function read(string $pathname): string
    {
        return $this->normalize(\file_get_contents($pathname));
    }

    /**
     * @param string $pathname
     * @param string $payload
     * @return void
     */
    private function write(string $pathname, string $payload): void
    {
        \file_put_contents($pathname, $this->normalize($payload));
    }

    /**
     * @param string $text
     * @return string
     */
    private function normalize(string $text): string
    {
        return \str_replace("\r", '', $text);
    }

    /**
     * @param mixed $payload
     * @return string
     */
    private function encode($payload): string
    {
        return \trim(\json_encode($payload, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT));
    }

    /**
     * @param mixed $payload
     * @return array
     */
    private function decode(string $payload): array
    {
        return \json_decode(\trim($payload), true, 512, \JSON_THROW_ON_ERROR);
    }

    /**
     * @param mixed $payload
     * @return array
     */
    private function format($payload): array
    {
        return $this->decode($this->encode($payload));
    }
}
