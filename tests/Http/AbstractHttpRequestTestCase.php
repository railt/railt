<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Http;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Http\RequestInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Http\Support\ConfigurableRequest;

/**
 * Class AbstractHttpRequestTestCase
 * @package Serafim\Railgun\Tests\Http
 */
abstract class AbstractHttpRequestTestCase extends AbstractTestCase
{
    /**
     * @throws \InvalidArgumentException
     */
    public function testJsonIsReadable(): void
    {
        $request = $this->request('{"query": "some"}');

        Assert::assertNotEquals('{}', $request->getQuery());
    }

    /**
     * @param string $body
     * @param bool $makeJson
     * @return RequestInterface
     */
    abstract protected function request(string $body, bool $makeJson = true): RequestInterface;

    /**
     * @return void
     */
    public function testJsonIsNotReadable(): void
    {
        $request = $this->request('{"query": "some"}', false);

        Assert::assertEquals('{}', $request->getQuery());
    }

    /**
     * @return void
     */
    public function testQueryIsReadable(): void
    {
        $expected = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $request = $this->request('{"query": "' . $expected . '"}');

        Assert::assertEquals($expected, $request->getQuery());
    }

    /**
     * @return void
     */
    public function testQueryDefaultValue(): void
    {
        $request = $this->request('');

        Assert::assertEquals('{}', $request->getQuery());
    }

    /**
     * @return void
     */
    public function testVariablesDefaultValue(): void
    {
        $request = $this->request('');

        Assert::assertNull($request->getVariables());
    }

    /**
     * @return void
     */
    public function testOperationDefaultValue(): void
    {
        $request = $this->request('');

        Assert::assertNull($request->getOperation());
    }

    /**
     * @return void
     */
    public function testVariablesIsReadable(): void
    {
        $expected = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $request = $this->request('{"variables": "' . $expected . '"}');

        Assert::assertEquals($expected, $request->getVariables());
    }

    /**
     * @return void
     */
    public function testOperationIsReadable(): void
    {
        $expected = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $request = $this->request('{"operation": "' . $expected . '"}');

        Assert::assertEquals($expected, $request->getOperation());
    }

    /**
     * @return void
     */
    public function testAllDataIsReadable(): void
    {
        $query = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $variables = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $operation = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $data = json_encode([
            'query'     => $query,
            'variables' => $variables,
            'operation' => $operation,
        ]);

        $request = $this->request($data);

        Assert::assertEquals($query, $request->getQuery());
        Assert::assertEquals($variables, $request->getVariables());
        Assert::assertEquals($operation, $request->getOperation());
    }

    /**
     * @return void
     */
    public function testPostIsReadable(): void
    {
        $query = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $variables = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $operation = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $_POST = [
            'query'     => $query,
            'variables' => $variables,
            'operation' => $operation,
        ];

        $request = $this->request('', false);

        Assert::assertEquals($query, $request->getQuery());
        Assert::assertEquals($variables, $request->getVariables());
        Assert::assertEquals($operation, $request->getOperation());
    }

    /**
     * @return void
     */
    public function testGetIsReadable(): void
    {
        $query = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $variables = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $operation = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $_GET = [
            'query'     => $query,
            'variables' => $variables,
            'operation' => $operation,
        ];

        $request = $this->request('', false);

        Assert::assertEquals($query, $request->getQuery());
        Assert::assertEquals($variables, $request->getVariables());
        Assert::assertEquals($operation, $request->getOperation());
    }

    /**
     * @return void
     */
    public function testGetHasLowerPriorityThanPost(): void
    {
        $query = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $variables = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $operation = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $_POST = [
            'query'     => $query,
            'variables' => $variables,
            'operation' => $operation,
        ];

        $_GET = [
            'query'     => (string)random_int(PHP_INT_MIN, PHP_INT_MAX),
            'variables' => (string)random_int(PHP_INT_MIN, PHP_INT_MAX),
            'operation' => (string)random_int(PHP_INT_MIN, PHP_INT_MAX),
        ];

        $request = $this->request('', false);

        Assert::assertEquals($query, $request->getQuery());
        Assert::assertEquals($variables, $request->getVariables());
        Assert::assertEquals($operation, $request->getOperation());
    }

    /**
     * @return void
     */
    public function testRawDataHasLowerPriorityThanJsonRequest(): void
    {
        $query = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $variables = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $operation = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $data = json_encode([
            'query'     => $query,
            'variables' => $variables,
            'operation' => $operation,
        ]);

        $_GET = $_POST = $_REQUEST = $HTTP_GET_VARS = $HTTP_POST_VARS = $HTTP_RAW_POST_DATA = [
            'query'     => (string)random_int(PHP_INT_MIN, PHP_INT_MAX),
            'variables' => (string)random_int(PHP_INT_MIN, PHP_INT_MAX),
            'operation' => (string)random_int(PHP_INT_MIN, PHP_INT_MAX),
        ];

        $request = $this->request($data);

        Assert::assertEquals($query, $request->getQuery());
        Assert::assertEquals($variables, $request->getVariables());
        Assert::assertEquals($operation, $request->getOperation());
    }

    /**
     * @return void
     */
    public function testConfigurableRequest(): void
    {
        $query = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $queryArgument = '_' . (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $variables = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $variablesArgument = '_' . (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $operation = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $operationArgument = '_' . (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $data = json_encode([
            $queryArgument => $query,
            $variablesArgument => $variables,
            $operationArgument => $operation,
        ]);

        /** @var ConfigurableRequest $request */
        $request = $this->request($data);

        $request
            ->setQueryArgument($queryArgument)
            ->setVariablesArgument($variablesArgument)
            ->setOperationArgument($operationArgument);

        Assert::assertEquals($query, $request->getQuery());
        Assert::assertEquals($variables, $request->getVariables());
        Assert::assertEquals($operation, $request->getOperation());
    }
}
