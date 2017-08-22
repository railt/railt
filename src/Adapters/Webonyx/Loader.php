<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use Railt\Adapters\Webonyx\Builder\ArgumentBuilder;
use Railt\Adapters\Webonyx\Builder\FieldBuilder;
use Railt\Adapters\Webonyx\Builder\ObjectTypeBuilder;
use Railt\Adapters\Webonyx\Builder\ScalarTypeBuilder;
use Railt\Adapters\Webonyx\Builder\Type\TypeBuilder;
use Railt\Reflection\Abstraction\ArgumentInterface;
use Railt\Reflection\Abstraction\DefinitionInterface;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Abstraction\FieldInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Abstraction\ObjectTypeInterface;
use Railt\Reflection\Abstraction\ScalarTypeInterface;
use Railt\Reflection\Abstraction\Type\TypeInterface;
use Railt\Reflection\Document;
use Railt\Routing\Router;
use Railt\Support\Dispatcher;

/**
 * Class BuilderResolver
 * @package Railt\Adapters\Webonyx
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
     * @var Router
     */
    private $router;

    /**
     * Loader constructor.
     * @param DocumentTypeInterface $document
     * @param Dispatcher $events
     * @param Router $router
     */
    public function __construct(DocumentTypeInterface $document, Dispatcher $events, Router $router)
    {
        $this->document = $document;
        $this->events = $events;
        $this->router = $router;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \LogicException
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
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
        $instance = new $class($this->events, $this->router, $this, $definition);

        return $instance->build();
    }

}
