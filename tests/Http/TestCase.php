<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\Http\Provider\ProviderInterface;
use Railt\Http\Request;
use Railt\Http\RequestInterface;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    private const JSON_OPTIONS =
        \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT | \JSON_PRETTY_PRINT |
        \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE | \JSON_PRESERVE_ZERO_FRACTION;

    /**
     * @return iterable|array[]
     */
    public function requestsDataProvider(): iterable
    {
        $providers = function () {
            yield 'GET Query String' => function (array $data = []) {
                return new Request($this->provider($data));
            };

            yield 'POST Query' => function (array $data = []) {
                return new Request($this->provider([], $data));
            };

            yield 'RAW JSON Body Query' => function (array $data = []) {
                $json = \json_encode($data, self::JSON_OPTIONS);

                return new Request($this->provider([], [], $json));
            };
        };

        $result = [];

        foreach ($providers() as $key => $provider) {
            $result[$key] = [$provider];
        }

        return $result;
    }

    /**
     * @param array $query
     * @param array $request
     * @param string $body
     * @return ProviderInterface
     */
    abstract protected function provider(array $query = [], array $request = [], string $body = ''): ProviderInterface;

    /**
     * @dataProvider requestsDataProvider
     *
     * @param \Closure $provider
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testBasicQuery(\Closure $provider): void
    {
        /** @var RequestInterface $request */
        $request = $provider(['query' => '{}']);

        $this->assertSame('{}', $request->getQuery());
        $this->assertCount(0, $request->getVariables());
        $this->assertNull($request->getOperationName());

        $this->assertCount(1, $request->getQueries());
        $this->assertFalse($request->isBatched());
    }

    /**
     * @dataProvider requestsDataProvider
     *
     * @param \Closure $provider
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNoQueries(\Closure $provider): void
    {
        /** @var RequestInterface $request */
        $request = $provider();

        $this->assertSame('', $request->getQuery());
        $this->assertCount(0, $request->getVariables());
        $this->assertNull($request->getOperationName());

        $this->assertCount(1, $request->getQueries());
        $this->assertFalse($request->isBatched());
    }

    /**
     * @dataProvider requestsDataProvider
     *
     * @param \Closure $provider
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testVariablesWithoutQuery(\Closure $provider): void
    {
        /** @var RequestInterface $request */
        $request = $provider(['variables' => ['a' => 23, 'b' => 42]]);

        $this->assertSame('', $request->getQuery());
        $this->assertCount(0, $request->getVariables());
        $this->assertNull($request->getOperationName());

        $this->assertCount(1, $request->getQueries());
        $this->assertFalse($request->isBatched());
    }

    /**
     * @dataProvider requestsDataProvider
     *
     * @param \Closure $provider
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testArrayVariables(\Closure $provider): void
    {
        /** @var RequestInterface $request */
        $request = $provider(['query' => '', 'variables' => ['a' => 23, 'b' => 42]]);

        $this->assertSame('', $request->getQuery());
        $this->assertCount(2, $request->getVariables());
        $this->assertSame(23, $request->getVariable('a'));
        $this->assertSame(42, $request->getVariable('b'));
        $this->assertNull($request->getOperationName());

        $this->assertCount(1, $request->getQueries());
        $this->assertFalse($request->isBatched());
    }

    /**
     * @dataProvider requestsDataProvider
     *
     * @param \Closure $provider
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testJsonVariables(\Closure $provider): void
    {
        /** @var RequestInterface $request */
        $request = $provider(['query' => '', 'variables' => '{"a": 23, "b": 42}']);

        $this->assertSame('', $request->getQuery());
        $this->assertCount(2, $request->getVariables());
        $this->assertSame(23, $request->getVariable('a'));
        $this->assertSame(42, $request->getVariable('b'));
        $this->assertNull($request->getOperationName());

        $this->assertCount(1, $request->getQueries());
        $this->assertFalse($request->isBatched());
    }

    /**
     * @dataProvider requestsDataProvider
     *
     * @param \Closure $provider
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testCorruptedVariables(\Closure $provider): void
    {
        /** @var RequestInterface $request */
        $request = $provider(['query' => 'query A {}', 'variables' => '{a:23,b:42}']); // Bad JSON

        $this->assertSame('query A {}', $request->getQuery());
        $this->assertCount(0, $request->getVariables());
        $this->assertNull($request->getOperationName());

        $this->assertCount(1, $request->getQueries());
        $this->assertFalse($request->isBatched());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testBadJsonQuery(): void
    {
        $request = new Request($this->provider([], [], '{query: ...}'));

        $this->assertSame('', $request->getQuery());
        $this->assertCount(0, $request->getVariables());
        $this->assertNull($request->getOperationName());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testJsonHasAHigherPriority(): void
    {
        $request = new Request($this->provider([
            'query'         => 'query DATA {}',
            'variables'     => ['some' => 23],
            'operationName' => 'data',
        ], [], '{"query": "query JSON {}", "operationName": "json"}'));

        $this->assertSame('query JSON {}', $request->getQuery());
        $this->assertCount(0, $request->getVariables());
        $this->assertSame('json', $request->getOperationName());
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testBadJsonHasAHigherPriority(): void
    {
        $request = new Request($this->provider([
            'query'         => 'query DATA {}',
            'variables'     => ['some' => 23],
            'operationName' => 'data',
        ], [], 'BAD JSON'));

        $this->assertSame('', $request->getQuery());
        $this->assertCount(0, $request->getVariables());
        $this->assertNull($request->getOperationName());
    }
}
