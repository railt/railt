<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Feature\FeatureContext;

use Railt\Parser\Ast\Node;
use PHPUnit\Framework\Assert;
use Phplrt\Visitor\Traverser;
use PHPUnit\Framework\Exception;
use Railt\Parser\Ast\DefinitionNode;
use Railt\SDL\Document\Document;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\Parser\Ast\TypeSystem\TypeDefinitionNode;
use Railt\SDL\Tests\Feature\FeatureContext\Visitor\SearchVisitor;
use Railt\SDL\Tests\Feature\FeatureContext\Support\TypeCastTrait;
use Railt\SDL\Tests\Feature\FeatureContext\Support\NumericalTrait;
use Railt\Parser\Ast\TypeSystem\Definition\EnumTypeDefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\UnionTypeDefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\ObjectTypeDefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\ScalarTypeDefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\InterfaceTypeDefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\InputObjectTypeDefinitionNode;

/**
 * Trait DocumentAssertionsTrait
 */
trait DocumentAssertionsTrait
{
    use NumericalTrait;
    use TypeCastTrait;

    /**
     * @var Document|null
     */
    private ?Document $document = null;

    /**
     * @var mixed|DefinitionNode
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
        Assert::assertCount($this->number($types), $this->last = $this->document->types());
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
        $definitions = $this->document->directives();

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
        $definitions = $this->document->types();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, EnumTypeDefinitionNode::class)
        );
    }

    /**
     * @param array $items
     * @param string $instanceof
     * @return array
     */
    private function documentFilter(array $items, string $instanceof): array
    {
        $filter = static function (TypeDefinitionNode $node) use ($instanceof) {
            return $node instanceof $instanceof;
        };

        return \array_filter($items, $filter);
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
        $definitions = $this->document->types();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, InputObjectTypeDefinitionNode::class)
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
        $definitions = $this->document->types();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, InterfaceTypeDefinitionNode::class)
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
        $definitions = $this->document->types();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, ObjectTypeDefinitionNode::class)
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
        $definitions = $this->document->types();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, ScalarTypeDefinitionNode::class)
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
        $definitions = $this->document->types();

        Assert::assertCount(
            $this->number($types),
            $this->last = $this->documentFilter($definitions, UnionTypeDefinitionNode::class)
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
        $schemas = $this->document->schemas();

        Assert::assertNotNull($this->last = (\reset($schemas) ?: null));
    }

    /**
     * @Then /^document not contains a schema type?/
     *
     * @return void
     * @throws ExpectationFailedException
     */
    public function thenDocumentNotContainSchemaTypes(): void
    {
        $schemas = $this->document->schemas();

        Assert::assertNull($this->last = (\reset($schemas) ?: null));
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
        Assert::assertCount(
            $this->number($directives),
            $this->last = $this->document->executions()
        );
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
            $this->last = $this->document->types();
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
