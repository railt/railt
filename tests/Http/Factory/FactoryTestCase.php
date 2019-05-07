<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Factory;

use Railt\Component\Http\Factory;
use Railt\Component\Http\Provider\ProviderInterface;
use Railt\Component\Http\RequestInterface;
use Railt\Tests\Http\TestCase;

/**
 * Class FactoryTestCase
 */
abstract class FactoryTestCase extends TestCase
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testRequestsCount(): void
    {
        $count = \count($this->getRequests());

        $this->assertTrue($count > 0);
        $this->assertCount($count, $this->requests());
    }

    /**
     * @return array
     */
    abstract protected function getRequests(): array;

    /**
     * @return iterable|RequestInterface[]
     */
    private function requests(): iterable
    {
        return Factory::create($this->getProvider());
    }

    /**
     * @return ProviderInterface
     */
    abstract protected function getProvider(): ProviderInterface;

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testQueries(): void
    {
        foreach ($this->requests() as $i => $request) {
            $this->assertSame($this->getRequests()[$i]->getQuery(), $request->getQuery());
        }
    }

    /**
     * @return void
     */
    public function testQueriesNotEmpty(): void
    {
        foreach ($this->requests() as $i => $request) {
            $this->assertNotSame('', $this->getRequests()[$i]->getQuery());
        }
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testVariables(): void
    {
        foreach ($this->requests() as $i => $request) {
            $this->assertSame($this->getRequests()[$i]->getVariables(), $request->getVariables());
        }
    }

    /**
     * @return void
     */
    public function testVariablesNotEmpty(): void
    {
        foreach ($this->requests() as $i => $request) {
            $this->assertGreaterThan(0, \count($this->getRequests()[$i]->getVariables()));
        }
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testOperationName(): void
    {
        foreach ($this->requests() as $i => $request) {
            $this->assertSame($this->getRequests()[$i]->getOperation(), $request->getOperation());
        }
    }

    /**
     * @return void
     */
    public function testOperationNotEmpty(): void
    {
        foreach ($this->requests() as $i => $request) {
            $this->assertNotNull($this->getRequests()[$i]->getOperation());
            $this->assertNotSame('', $this->getRequests()[$i]->getOperation());
        }
    }

    /**
     * @param int $index
     * @return int
     * @throws \Exception
     */
    protected function int(int $index): int
    {
        if (! isset($this->values[$index])) {
            return $this->values[$index] = \random_int(\PHP_INT_MIN, \PHP_INT_MAX);
        }

        return (int)$this->values[$index];
    }

    /**
     * @param int $index
     * @return string
     * @throws \Exception
     */
    protected function string(int $index): string
    {
        if (! isset($this->values[$index])) {
            $string = \base64_encode(\random_bytes(32));

            return $this->values[$index] = \str_replace(['/', '+', '='], '', $string);
        }

        return (string)$this->values[$index];
    }
}
