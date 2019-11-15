<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use Railt\SDL\Document;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Builder\Factory;
use Railt\SDL\Executor\Registry;
use Railt\SDL\Ast\DefinitionNode;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class ExtensionExecutor
 */
abstract class ExtensionExecutor extends Visitor
{
    /**
     * @var Factory
     */
    private Factory $factory;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var Document
     */
    protected Document $document;

    /**
     * ExtensionExecutor constructor.
     *
     * @param Factory $factory
     * @param Document $document
     * @param Registry $registry
     */
    public function __construct(Factory $factory, Document $document, Registry $registry)
    {
        $this->factory = $factory;
        $this->registry = $registry;
        $this->document = $document;
    }

    /**
     * @param DefinitionNode $node
     * @return DefinitionInterface
     */
    protected function build(DefinitionNode $node): DefinitionInterface
    {
        return $this->factory->build($node, $this->registry);
    }

    /**
     * @param string $type
     * @return NamedTypeInterface
     */
    protected function fetch(string $type): NamedTypeInterface
    {
        return $this->factory->fetch($type, $this->registry);
    }
}
