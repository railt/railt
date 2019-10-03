<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Feature\RequestFeatureContext;

use Railt\Http\Request;
use PHPUnit\Framework\Assert;
use Railt\Http\RequestInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Trait RequestAssertionsTrait
 */
trait RequestAssertionsTrait
{
    /**
     * @var Request|null
     */
    protected ?Request $request = null;

    /**
     * @Then /^(?:request|where) query is "(.+?)"$/
     *
     * @param string $needle
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenQueryIs(string $needle): void
    {
        $this->thenRequestExists();

        Assert::assertSame($needle, $this->request->getQuery());
    }

    /**
     * @Then /^(?:where\h)?request (?:not null|exists|defined)$/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenRequestExists(): void
    {
        Assert::assertNotNull($this->request, 'Request was not created');
    }

    /**
     * @Then /^(?:request|where) query is (?:not defined|empty|null)$/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenQueryIsEmpty(): void
    {
        $this->thenRequestExists();

        Assert::assertTrue($this->request->isEmpty());
    }

    /**
     * @Then /^(?:request|where) variables has been defined$/
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenVariablesIsEmpty(): void
    {
        $this->thenRequestExists();

        Assert::assertCount(0, $this->request->getVariables());
    }

    /**
     * @Then /^(?:request|where) variables is "(.+?)"$/
     *
     * @param string $needle
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenVariablesIs(string $needle): void
    {
        $this->thenRequestExists();

        Assert::assertSame($needle, \json_encode($this->request->getVariables(), \JSON_THROW_ON_ERROR));
    }

    /**
     * @Then /^(?:request|where) variable "(.+?)" is "(.+?)"$/
     *
     * @param string $name
     * @param string $needle
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenVariableIsString(string $name, string $needle): void
    {
        $this->thenVariableIs($name, 'string', $needle);
    }

    /**
     * @Then /^(?:request|where) variable "(.+?)" is (\w+) "(.+?)"$/
     *
     * @param string $name
     * @param string $type
     * @param string $needle
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenVariableIs(string $name, string $type, string $needle): void
    {
        $this->thenRequestExists();

        Assert::assertSame($this->cast($type, $needle), $this->request->getVariable($name));
    }


    /**
     * @Then /^(?:request|where) variable "(.+?)" is (?:not defined|empty|null)/
     *
     * @param string $name
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenVariableIsEmpty(string $name): void
    {
        $this->thenRequestExists();

        Assert::assertNull($this->request->getVariable($name));
    }

    /**
     * @Then /^(?:request|where) variable "(.+?)" contains "(.+?)"$/
     *
     * @param string $name
     * @param string $needle
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenVariableContainsString(string $name, string $needle): void
    {
        $this->thenVariableContains($name, 'string', $needle);
    }

    /**
     * @Then /^(?:request|where) variable "(.+?)" contains (\w+) "(.+?)"$/
     *
     * @param string $name
     * @param string $type
     * @param string $needle
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenVariableContains(string $name, string $type, string $needle): void
    {
        $this->thenRequestExists();

        Assert::assertSame(
            $this->cast($type, $needle),
            $this->depth($name, $this->request->getVariables())
        );
    }

    /**
     * @Then /^(?:request|where) variable "(.+?)" contains nothing$/
     *
     * @param string $name
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenVariableContainsNothing(string $name): void
    {
        $this->thenRequestExists();

        Assert::assertNull($this->depth($name, $this->request->getVariables()));
    }

    /**
     * @Then /^(?:request|where) contains ([a-z0-9]+) variable(?:s)$/
     *
     * @param string $count
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenVariableCount(string $count): void
    {
        $this->thenRequestExists();

        Assert::assertCount($this->number($count), $this->request->getVariables());
    }

    /**
     * @Then /^(?:request|where) operation name is "(.+?)"$/
     *
     * @param string $name
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenOperationIs(string $name): void
    {
        $this->thenRequestExists();

        Assert::assertSame($name, $this->request->getOperationName());
    }

    /**
     * @Then /^(?:request|where) operation name is (?:not defined|empty|null)$/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenOperationIsEmpty(): void
    {
        $this->thenRequestExists();

        Assert::assertFalse($this->request->hasOperationName());
    }
}
