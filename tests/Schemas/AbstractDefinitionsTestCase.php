<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Schemas;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Schema\AbstractSchema;
use Serafim\Railgun\Schema\Definitions\TypeDefinition;
use Serafim\Railgun\Schema\SchemaInterface;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Types\Registry;

/**
 * Class AbstractDefinitionsTestCase
 * @package Serafim\Railgun\Tests\Schemas
 */
abstract class AbstractDefinitionsTestCase extends AbstractTestCase
{
    /**
     * @return SchemaInterface|AbstractSchema
     */
    abstract protected function getSchema(): SchemaInterface;

    /**
     * @param $definition
     * @param string $type
     * @param bool $isList
     */
    protected function assert($definition, string $type, bool $isList = false)
    {
        /** @var TypeDefinition $definition */
        $definition = method_exists($definition, 'getTypeDefinition')
            ? $definition->getTypeDefinition()
            : $definition;

        Assert::assertEquals($type, $definition->getTypeName());
        Assert::assertEquals($isList, $definition->isList());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIdMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->id()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_ID, false);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIdsMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->ids()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_ID, true);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIntegerMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->integer()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_INT, false);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testIntegersMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->integers()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_INT, true);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testStringMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->string()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_STRING, false);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testStringsMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->strings()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_STRING, true);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testBooleanMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->boolean()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_BOOLEAN, false);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testBooleansMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->booleans()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_BOOLEAN, true);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testFloatMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->float()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_FLOAT, false);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testFloatsMethod(): void
    {
        /** @var InteractWithName $definition */
        $definition = $this->getSchema()->floats()->build();

        $this->assert($definition, Registry::INTERNAL_TYPE_FLOAT, true);
    }

    /**
     * @return void
     */
    public function testExtendable(): void
    {
        $schema = $this->getSchema();

        $schema->extend('some', function() use ($schema) {
            return $schema->integer();
        });

        $this->assert($schema->some()->build(), Registry::INTERNAL_TYPE_INT);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     */
    public function testBadExtension(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $schema = $this->getSchema();

        $this->assert($schema->some()->build(), Registry::INTERNAL_TYPE_INT);
    }
}
