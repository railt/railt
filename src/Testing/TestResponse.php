<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing;

use PHPUnit\Framework\Assert;
use Railt\Http\GraphQLException;
use Railt\Http\ResponseInterface;

/**
 * @property TestResponse $successful
 * @property TestResponse $hasErrors
 */
class TestResponse extends TestValue
{
    /**
     * Response constructor.
     * @param ResponseInterface $response
     */
    final public function __construct(ResponseInterface $response)
    {
        $this->field    = '<response>';
        $this->value    = $response->toArray();
        $this->response = $response;
        $this->isExists = true;
    }

    /**
     * @return TestResponse
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function successful(): self
    {
        $actual = $this->response->isSuccessful();

        $message = 'Response status code [' . $this->response->getStatusCode() .
            '] is not a successful status code' . $this->exceptionsMessage();

        Assert::assertTrue($actual, $message);

        return $this;
    }

    /**
     * @return TestResponse
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function hasErrors(): self
    {
        $actual = $this->response->hasErrors();

        $message = 'Response status code [' . $this->response->getStatusCode() .
            '] is not a error status code' . $this->exceptionsMessage();

        Assert::assertTrue($actual, $message);

        return $this;
    }

    /**
     * @param int $statusCode
     * @return TestResponse
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function status(int $statusCode): self
    {
        $actual = $this->response->getStatusCode();

        $message = 'Expected status code ' . $statusCode . ' but received ' .
            $actual . $this->exceptionsMessage();

        Assert::assertEquals($actual, $statusCode, $message);

        return $this;
    }

    /**
     * @return string
     */
    public function exceptionsMessage(): string
    {
        $exceptions = $this->response->getExceptions();

        $errors = \array_map(function (\Throwable $e): string {
            return \vsprintf(' - [%s] %s with message "%s" in %s:%d', [
                $e instanceof GraphQLException ? 'public' : 'internal',
                \get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
            ]);
        }, $exceptions);

        return \vsprintf(' and returns a set of errors (%d): %s', [
            \count($errors),
            \PHP_EOL . \implode(\PHP_EOL, $errors),
        ]);
    }

    /**
     * @param string $name
     * @return TestValue
     */
    public function where(string $name): TestValue
    {
        [$result, $exists] = $this->get($name, $this->response->toArray());

        return new TestValue($name, $result, $this, $exists);
    }

    /**
     * @param string $name
     * @return TestValue
     */
    public function response(string $name): TestValue
    {
        [$result, $exists] = $this->get($name, $this->response->getData());

        return new TestValue($name, $result, $this, $exists);
    }

    /**
     * @param int $number
     * @return TestValue
     */
    public function error(int $number = 0): TestValue
    {
        $name = (string)$number;

        [$result, $exists] = $this->get($name, $this->response->getErrors());

        return new TestValue($name, $result, $this, $exists);
    }

    /**
     * @return TestValue
     */
    public function errors(): TestValue
    {
        return $this->where('errors');
    }

    /**
     * @return void
     */
    public function dump(): void
    {
        echo $this->response->render();
    }
}
