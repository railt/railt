<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Unit\Request;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Request;
use Railt\Http\Request\ProvidesVariablesInterface;
use Railt\Http\Tests\Unit\TestCase;

/**
 * Class VariablesTestCase
 */
class VariablesTestCase extends TestCase
{
    /**
     * @return array
     */
    public function iterableProvider(): array
    {
        $generator = static function () {
            yield 'a' => 42;
        };

        return [
            'Generator'     => [$generator()],
            'ArrayIterator' => [new \ArrayIterator(['a' => 42])],
            'array'         => [['a' => 42]],
        ];
    }

    /**
     * @dataProvider iterableProvider
     *
     * @param iterable $iterator
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function testVariablesCount(iterable $iterator): void
    {
        $request = $this->request($iterator);

        $this->assertCount(1, $request);
    }

    /**
     * @param iterable $variables
     * @return ProvidesVariablesInterface
     */
    private function request(iterable $variables): ProvidesVariablesInterface
    {
        return new Request('', $variables);
    }

    /**
     * @dataProvider iterableProvider
     *
     * @param iterable $iterator
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVariablesExisting(iterable $iterator): void
    {
        $request = $this->request($iterator);

        $this->assertTrue($request->hasVariable('a'));
    }

    /**
     * @dataProvider iterableProvider
     *
     * @param iterable $iterator
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVariablesNotExisting(iterable $iterator): void
    {
        $request = $this->request($iterator);

        $this->assertFalse($request->hasVariable('ab'));
        $this->assertFalse($request->hasVariable('A'));
    }

    /**
     * @dataProvider iterableProvider
     *
     * @param iterable $iterator
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVariableValue(iterable $iterator): void
    {
        $request = $this->request($iterator);

        $this->assertSame(42, $request->getVariable('a'));
        $this->assertNull($request->getVariable('xxx'));
    }

    /**
     * @dataProvider iterableProvider
     *
     * @param iterable $iterator
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVariablesFetching(iterable $iterator): void
    {
        $request = $this->request($iterator);

        $this->assertSame(['a' => 42], $request->getVariables());
    }
}
