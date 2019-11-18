<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Linker;

use Railt\SDL\Document;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Executor\Registry;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Type\NamedTypeNode;

/**
 * Class TypeLinker
 */
abstract class TypeLinker extends Visitor
{
    /**
     * @var Document
     */
    protected Document $document;

    /**
     * @var Registry
     */
    protected Registry $registry;

    /**
     * @var iterable|callable[]
     */
    protected iterable $loaders;

    /**
     * TypeLinker constructor.
     *
     * @param Document $document
     * @param Registry $registry
     * @param iterable|callable[] $loaders
     */
    public function __construct(Document $document, Registry $registry, iterable $loaders)
    {
        $this->document = $document;
        $this->registry = $registry;
        $this->loaders = $loaders;
    }

    /**
     * @param DefinitionNode $node
     * @param int $type
     * @param string|null $name
     * @return bool
     */
    protected function loaded(DefinitionNode $node, int $type, ?string $name): bool
    {
        if (! $this->exists($node)) {
            foreach ($this->loaders as $loader) {
                $loader($type, $name);

                if ($this->exists($node)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @param DefinitionNode|NamedTypeNode $type
     * @return bool
     */
    protected function exists(DefinitionNode $type): bool
    {
        return isset($this->registry->typeMap[$type->name->value])
            || $this->document->hasType($type->name->value);
    }
}
