<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Definitions;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Support\NameableInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Schema\Definitions\TypeDefinition;
use Serafim\Railgun\Schema\Definitions\FieldDefinition;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinition;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinitionInterface;
use Serafim\Railgun\Tests\Concerns\ContainsName;

/**
 * Class FieldDefinitionTestCase
 * @package Serafim\Railgun\Tests\Definitions
 */
class FieldDefinitionTestCase extends AbstractTestCase
{
    use ContainsName;

    /**
     * @return \Traversable
     */
    protected function mockDefaultFormattedName(): \Traversable
    {
        yield 'new name' => 'newname';
    }

    /**
     * @return NameableInterface
     */
    protected function mock(): NameableInterface
    {
        return $this->fieldDefinition()
            ->rename('test');
    }

    /**
     * @param array $arguments
     * @param string|null $deprecation
     * @param \Closure|null $then
     * @return FieldDefinition
     */
    protected function fieldDefinition(array $arguments = [], ?string $deprecation = null, ?\Closure $then = null)
    {
        return new FieldDefinition(
            new TypeDefinition((string)random_int(PHP_INT_MIN, PHP_INT_MAX)),
            $arguments,
            $deprecation,
            $then
        );
    }

    /**
     * @return void
     */
    public function testArguments(): void
    {
        $expectedKey = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);
        $expectedValue = (string)random_int(PHP_INT_MIN, PHP_INT_MAX);

        $field = $this->fieldDefinition([
            $expectedKey => new ArgumentDefinition(new TypeDefinition($expectedValue))
        ]);

        foreach ($field->getArguments() as $name => $value) {
            Assert::assertInstanceOf(ArgumentDefinitionInterface::class, $value);
            Assert::assertEquals($expectedValue, $value->getTypeDefinition()->getTypeName());
            Assert::assertEquals($expectedKey, $name);
        }
    }

    /**
     * @return void
     */
    public function testNotResolvable(): void
    {
        $field = $this->fieldDefinition();

        Assert::assertFalse($field->isResolvable());
    }

    /**
     * @return void
     */
    public function testResolvable(): void
    {
        $field = $this->fieldDefinition([], null, function() {});

        Assert::assertTrue($field->isResolvable());
    }

    /**
     * @return void
     */
    public function testNotDeprecated(): void
    {
        $field = $this->fieldDefinition();

        Assert::assertFalse($field->isDeprecated());
    }

    /**
     * @return void
     */
    public function testDeprecated(): void
    {
        $field = $this->fieldDefinition([], 'message');

        Assert::assertTrue($field->isDeprecated());
    }

    /**
     * @return void
     */
    public function testDefaultDeprecatedMessage(): void
    {
        $field = $this->fieldDefinition();

        Assert::assertEmpty($field->getDeprecationReason());
    }

    /**
     * @return void
     */
    public function testDeprecatedMessage(): void
    {
        $field = $this->fieldDefinition([], 'deprecation message');

        Assert::assertEquals('deprecation message', $field->getDeprecationReason());
    }
}
