<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx;

use Serafim\Railgun\Adapters\Webonyx\Builder\FieldBuilder;
use Serafim\Railgun\Adapters\Webonyx\Builder\ObjectTypeBuilder;
use Serafim\Railgun\Adapters\Webonyx\Builder\ScalarTypeBuilder;
use Serafim\Railgun\Adapters\Webonyx\Builder\SchemaTypeBuilder;
use Serafim\Railgun\Adapters\Webonyx\Builder\Type\TypeBuilder;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\ScalarTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;
use Serafim\Railgun\Reflection\Document;

/**
 * Class BuilderResolver
 * @package Serafim\Railgun\Adapters\Webonyx
 */
class Loader
{
    /**
     * @var DocumentTypeInterface|Document
     */
    private $document;

    /**
     * @var array
     */
    private $dictionary = [];

    /**
     * @var array
     */
    private $mapping = [
        // Named types
        ObjectTypeInterface::class => ObjectTypeBuilder::class,
        ScalarTypeInterface::class => ScalarTypeBuilder::class,

        // Partials
        FieldInterface::class => FieldBuilder::class,
        TypeInterface::class => TypeBuilder::class,
    ];

    /**
     * Builder constructor.
     * @param DocumentTypeInterface|Document $document
     */
    public function __construct(DocumentTypeInterface $document)
    {
        $this->document = $document;
    }

    /**
     * @param DocumentTypeInterface $document
     * @return Loader
     */
    public static function new(DocumentTypeInterface $document): Loader
    {
        return new static($document);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \LogicException
     * @throws \Serafim\Railgun\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    public function resolve(string $name)
    {
        $definition = $this->document->load($name);

        foreach ($this->mapping as $def => $builder) {
            if ($definition instanceof $def) {
                return $this->makeCached($definition, $builder);
            }
        }

        throw new \LogicException($definition->getTypeName() . ' not buildable yet');
    }

    /**
     * @param DefinitionInterface|TypeInterface $definition
     * @param string $class
     * @return mixed
     */
    private function makeCached($definition, string $class)
    {
        if ($definition instanceof NamedDefinitionInterface) {
            return $this->makeCachedNamedDefinition($definition, $class);
        }

        return $this->make($definition, $class);
    }

    /**
     * @param NamedDefinitionInterface $definition
     * @param string $class
     * @return mixed
     */
    private function makeCachedNamedDefinition(NamedDefinitionInterface $definition, string $class)
    {
        if (!isset($this->dictionary[$definition->getName()])) {
            $this->dictionary[$definition->getName()] = $this->make($definition, $class);
        }

        return $this->dictionary[$definition->getName()];
    }

    /**
     * @param DefinitionInterface|TypeInterface $definition
     * @param string $class
     * @return mixed
     */
    public function make($definition, string $class)
    {
        /** @var BuilderInterface $instance */
        $instance = new $class($this, $definition);

        return $instance->build();
    }
}
