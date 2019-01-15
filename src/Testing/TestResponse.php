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
use Railt\Http\Exception\GraphQLExceptionInterface;
use Railt\Http\ResponseInterface;

/**
 * @property TestResponse $successful
 * @property TestResponse $hasErrors
 * @property TestResponse $dump
 * @property void $dd
 */
class TestResponse extends TestValue
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Response constructor.
     * @param ResponseInterface $response
     */
    final public function __construct(ResponseInterface $response)
    {
        $this->field = '<response>';
        $this->value = $response->toArray();
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
                $e instanceof GraphQLExceptionInterface ? 'public' : 'internal',
                \get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
            ]);
        }, \iterator_to_array($exceptions));

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
     * @return $this|TestValue
     */
    public function dump(): TestValue
    {
        echo $this->response->render();

        return $this;
    }
}
