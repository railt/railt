<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Feature\FeatureContext;

use Railt\SDL\Ast\Node;
use PHPUnit\Framework\Assert;
use Phplrt\Visitor\Traverser;
use PHPUnit\Framework\Exception;
use Railt\Contracts\SDL\DocumentInterface;
use PHPUnit\Framework\ExpectationFailedException;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\UnionTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ObjectTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\ScalarTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\InputObjectTypeInterface;
use Railt\SDL\Tests\Feature\FeatureContext\Visitor\SearchVisitor;
use Railt\SDL\Tests\Feature\FeatureContext\Support\TypeCastTrait;
use Railt\SDL\Tests\Feature\FeatureContext\Support\NumericalTrait;

/**
 * Trait DocumentAssertionsTrait
 */
trait DocumentAssertionsTrait
{
    use NumericalTrait;
    use TypeCastTrait;

    /**
     * @var DocumentInterface|null
     */
    private ?DocumentInterface $document = null;

    /**
     * @var mixed|DefinitionInterface
     */
    private $last;

    /**
     * @Then /^no document exists/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenNoDocument(): void
    {
        Assert::assertNull($this->document);
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainTypes($types): void
    {
        $this->thenNoErrors();

        Assert::assertCount($this->number($types), $this->last = $this->document->getTypeMap());
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) directive definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainDirectiveTypes($types): void
    {
        $this->thenNoErrors();
        $definitions = $this->document->getDirectives();

        Assert::assertCount($this->number($types), $this->last = $definitions);
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) enum definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainEnumTypes($types): void
    {
        $this->thenNoErrors();
        $definitions = $this->document->getTypeMap();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, EnumTypeInterface::class)
        );
    }

    /**
     * @param array $items
     * @param string $instanceof
     * @return array
     */
    private function documentFilter(iterable $items, string $instanceof): array
    {
        $filter = static function (DefinitionInterface $node) use ($instanceof) {
            return $node instanceof $instanceof;
        };

        return \array_filter(\is_array($items) ? $items : \iterator_to_array($items), $filter);
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) input object definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainInputObjectTypes($types): void
    {
        $this->thenNoErrors();

        $definitions = $this->document->getTypeMap();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, InputObjectTypeInterface::class)
        );
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) interface definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainInterfaceTypes($types): void
    {
        $this->thenNoErrors();
        $definitions = $this->document->getTypeMap();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, InterfaceTypeInterface::class)
        );
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) object definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainObjectTypes($types): void
    {
        $this->thenNoErrors();
        $definitions = $this->document->getTypeMap();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, ObjectTypeInterface::class)
        );
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) scalar definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainScalarTypes($types): void
    {
        $this->thenNoErrors();
        $definitions = $this->document->getTypeMap();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, ScalarTypeInterface::class)
        );
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) union definition(?:s)?/
     *
     * @param int|string $types
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainUnionTypes($types): void
    {
        $this->thenNoErrors();
        $definitions = $this->document->getTypeMap();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, UnionTypeInterface::class)
        );
    }

    /**
     * @Then /^(?:document contains )?a schema type?/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenDocumentContainSchemaTypes(): void
    {
        $this->thenNoErrors();
        $schema = $this->document->getSchema();

        Assert::assertNotNull($this->last = $schema);
    }

    /**
     * @Then /^document not contains a schema type?/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenDocumentNotContainSchemaTypes(): void
    {
        $this->thenNoErrors();
        $schema = $this->document->getSchema();

        Assert::assertNull($this->last = $schema);
    }

    /**
     * @Then /^(?:document contains )?([\w\d]+) execution(?:s)?$/
     *
     * @param int|string $directives
     * @return void
     * @throws ExpectationFailedException
     * @throws Exception
     */
    public function thenDocumentContainDirectives($directives): void
    {
        throw new \LogicException(__METHOD__ . ' test not implemented yet');
    }

    /**
     * @Then /^where "([^"]+)" like "([^"]+)"$/
     *
     * @param string $field
     * @param mixed $value
     * @return void
     */
    public function wherePropertyIs(string $field, $value): void
    {
        $this->wherePropertyOfIndexIs($field, 0, $value);
    }

    /**
     * @Then /^where "([^"]+)" of ([\w\d]+) item like "([^"]+)"$/
     *
     * @param int|string $index
     * @param string $field
     * @param mixed $value
     * @return void
     */
    public function wherePropertyOfIndexIs(string $field, $index, $value): void
    {
        $this->lastWhere($field, $index, static function ($context) use ($value) {
            Assert::assertEquals($context, $value);
        });
    }

    /**
     * @param string $field
     * @param int|string $index
     * @param \Closure $assertion
     * @return void
     */
    private function lastWhere(string $field, $index, \Closure $assertion): void
    {
        $this->lastIs($index, static function (Node $node) use ($field, $assertion) {
            $context = $node;

            $chunks = \array_filter(\preg_split('/\W/u', $field));

            foreach ($chunks as $chunk) {
                $context = $context->$chunk;
            }

            $assertion($context);
        });
    }

    /**
     * @param int|string $index
     * @param \Closure $fn
     * @return void
     */
    private function lastIs($index, \Closure $fn): void
    {
        if ($this->last === null) {
            $this->thenNoErrors();

            $this->last = $this->document->getTypeMap();
        }

        (new Traverser())
            ->with(new SearchVisitor($this->number($index), $fn))
            ->traverse($this->last);
    }

    /**
     * @Then /^where "([^"]+)" is (\w+) "([^"]+)"$/
     *
     * @param string $field
     * @param string $type
     * @param mixed $value
     * @return void
     */
    public function wherePropertyTypeIs(string $field, string $type, $value): void
    {
        $this->wherePropertyTypeOfIndexIs($field, 0, $type, $value);
    }

    /**
     * @Then /^where "([^"]+)" of ([\w\d]+) item is (\w+) "([^"]+)"$/
     *
     * @param string $field
     * @param int|string $index
     * @param string $type
     * @param mixed $value
     * @return void
     */
    public function wherePropertyTypeOfIndexIs(string $field, $index, string $type, $value): void
    {
        $this->lastWhere($field, $index, function ($context) use ($field, $type, $value) {
            Assert::assertSame($context, $this->cast($type, $value));
        });
    }
}
