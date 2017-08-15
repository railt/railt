<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Webonyx;

use Railgun\Endpoint;
use Railgun\Adapters\Dispatcher;
use Railgun\Reflection\Abstraction\ArgumentInterface;
use Railgun\Reflection\Abstraction\DefinitionInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Reflection\Abstraction\FieldInterface;
use Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Railgun\Reflection\Abstraction\ScalarTypeInterface;
use Railgun\Reflection\Abstraction\Type\TypeInterface;
use Railgun\Reflection\Document;
use Railgun\Webonyx\Builder\ArgumentBuilder;
use Railgun\Webonyx\Builder\FieldBuilder;
use Railgun\Webonyx\Builder\ObjectTypeBuilder;
use Railgun\Webonyx\Builder\ScalarTypeBuilder;
use Railgun\Webonyx\Builder\Type\TypeBuilder;

/**
 * Class BuilderResolver
 * @package Railgun\Webonyx
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
        FieldInterface::class      => FieldBuilder::class,
        TypeInterface::class       => TypeBuilder::class,
        ArgumentInterface::class   => ArgumentBuilder::class,
    ];

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * Loader constructor.
     * @param DocumentTypeInterface $document
     * @param Dispatcher $events
     */
    public function __construct(DocumentTypeInterface $document, Dispatcher $events)
    {
        $this->document = $document;
        $this->events = $events;
    }

    /**
     * @param DocumentTypeInterface $document
     * @param Dispatcher $events
     * @return Loader
     */
    public static function new(DocumentTypeInterface $document, Dispatcher $events): Loader
    {
        return new static($document, $events);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \LogicException
     * @throws \Railgun\Exceptions\UnexpectedTokenException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
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
     * @throws \LogicException
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
     * @throws \LogicException
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
     * @throws \LogicException
     */
    public function make($definition, string $class)
    {
        /** @var BuilderInterface $instance */
        $instance = new $class($this->events, $this, $definition);

        return $instance->build();
    }

}