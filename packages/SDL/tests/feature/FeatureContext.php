<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Feature;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Railt\SDL\Compiler;
use Railt\SDL\Tests\Feature\FeatureContext\DocumentAssertionsTrait;
use Railt\SDL\Tests\Feature\FeatureContext\ErrorAssertionsTrait;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    use ErrorAssertionsTrait;
    use DocumentAssertionsTrait;

    /**
     * @When /^I define the schema:/
     *
     * @param PyStringNode $schema
     * @return void
     * @throws \Throwable
     */
    public function whenDefineSchemaText(PyStringNode $schema): void
    {
        $this->whenDefineSchema(\trim($schema->getRaw()));
    }

    /**
     * @When /^I define the schema "([^"]+)"$/
     *
     * @param string $schema
     * @return void
     * @throws \Throwable
     */
    public function whenDefineSchema(string $schema): void
    {
        $this->document = $this->wrapErrors(static function () use ($schema) {
            return (new Compiler(Compiler::SPEC_RAW))->compile($schema);
        });
    }

    /**
     * @When /^I define the schema with stdlib:/
     *
     * @param PyStringNode $schema
     * @return void
     * @throws \Throwable
     */
    public function whenDefineSchemaWithStdlibText(PyStringNode $schema): void
    {
        $this->whenDefineSchemaWithStdlib(\trim($schema->getRaw()));
    }

    /**
     * @When /^I define the schema "([^"]+)" with stdlib$/
     *
     * @param string $schema
     * @return void
     * @throws \Throwable
     */
    public function whenDefineSchemaWithStdlib(string $schema): void
    {
        $this->document = $this->wrapErrors(static function () use ($schema) {
            return (new Compiler(Compiler::MODE_EMPTY))->compile($schema);
        });
    }
}
