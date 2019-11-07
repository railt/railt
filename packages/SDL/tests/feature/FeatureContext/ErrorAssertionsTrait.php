<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Feature\FeatureContext;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Trait ErrorAssertionsTrait
 */
trait ErrorAssertionsTrait
{
    /**
     * @var \Throwable|null
     */
    protected ?\Throwable $error = null;

    /**
     * @Then /^GraphQL (.+?) occurs$/
     *
     * @param string $class
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenGraphQLErrorOccurs(string $class): void
    {
        $class = \implode('', \explode(' ', \ucwords(\trim($class, '\\'))));

        $fqn = '\\Railt\\SDL\\Exception\\' . $class;

        if (! \class_exists($fqn)) {
            throw new \InvalidArgumentException('"' . $class . '" is not a valid exception class name');
        }

        $this->thenErrorOccurs(\Throwable::class);

        Assert::assertInstanceOf($fqn, $this->error, $this->errorMessage());
    }

    /**
     * @Then /^error ([a-zA-Z_\\]+) occurs$/
     *
     * @param string $class
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenErrorOccurs(string $class): void
    {
        Assert::assertNotNull($this->error, 'Condition Not Met: No errors were thrown');

        Assert::assertInstanceOf($class, $this->error, $this->errorMessage());
    }

    /**
     * @return string
     */
    private function errorMessage(): string
    {
        if ($this->error instanceof \Throwable) {
            return 'Exception ' . \get_class($this->error) . ' with message "' . $this->error->getMessage() . '"';
        }

        return \get_class($this->error) . ' is not an instance of Throwable';
    }

    /**
     * @Then /^error message is: (.+)$/
     *
     * @param string $message
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenErrorMessage(string $message): void
    {
        $this->thenErrorOccurs(\Throwable::class);

        Assert::assertStringMatchesFormat($message, $this->error->getMessage(), $this->errorMessage());
    }

    /**
     * @Then /^error code is (\d+)$/
     *
     * @param int $code
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenErrorCodeIs(int $code): void
    {
        $this->thenErrorOccurs(\Throwable::class);

        Assert::assertSame($code, $this->error->getCode());
    }

    /**
     * @Then /^no errors occurred$/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenNoErrors(): void
    {
        Assert::assertNull($this->error);
    }

    /**
     * @param \Closure $expr
     * @return mixed
     */
    protected function wrapErrors(\Closure $expr)
    {
        try {
            return $expr();
        } catch (\Throwable $e) {
            $this->error = $e;
        }

        return null;
    }
}
