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
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Trait RequestFeatureTrait
 */
trait RequestFeatureTrait
{
    use RequestAssertionsTrait;

    /**
     * @var string
     */
    protected string $query = '';

    /**
     * @var array
     */
    protected array $variables = [];

    /**
     * @var string|null
     */
    protected ?string $operation = null;

    /**
     * @When /^create request$/
     *
     * @return void
     */
    public function whenCreateRequest(): void
    {
        $this->request = new Request($this->query, $this->variables, $this->operation);
    }

    /**
     * @When /^create request with query "(.+?)"$/
     *
     * @param string $query
     * @return void
     */
    public function whenCreateRequestWithQuery(string $query): void
    {
        $this->request = new Request($query, $this->variables, $this->operation);
    }

    /**
     * @Given /^GraphQL operation "(.+?)"$/
     *
     * @param string $operation
     * @return void
     */
    public function givenOperation(string $operation): void
    {
        $this->operation = $operation;
    }

    /**
     * @Given /^GraphQL query "(.+?)"$/
     *
     * @param string $query
     * @return void
     */
    public function givenQuery(string $query): void
    {
        $this->query = $query;
    }

    /**
     * @Given /^GraphQL query:$/
     *
     * @param PyStringNode $query
     * @return void
     */
    public function givenQueryText(PyStringNode $query): void
    {
        $this->query = \trim($query->getRaw());
    }

    /**
     * @Given /^GraphQL variable "([^"]+)" defined by (\w+) "([^"]+)"$/
     *
     * @param string $name
     * @param string $type
     * @param string $value
     * @return void
     */
    public function withVariable(string $name, string $type, string $value): void
    {
        $this->variables[$name] = $this->cast($type, $value);
    }

    /**
     * @Given /^GraphQL variable "([^"]+)" defined by "([^"]+)"$/
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function withStringVariable(string $name, string $value): void
    {
        $this->withVariable($name, 'string', $value);
    }

    /**
     * @Given /^GraphQL variables:$/
     *
     * @param TableNode $variables
     * @return void
     */
    public function withVariablesTable(TableNode $variables): void
    {
        foreach ($variables->getRows() as $vars) {
            [$name, $value, $type] = [...$vars, 'string'];

            $this->variables[$name] = $this->cast($type, $value);
        }
    }
}
