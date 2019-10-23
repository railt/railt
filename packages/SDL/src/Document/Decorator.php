<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Document;

/**
 * Class Decorator
 */
class Decorator extends MutableDocument
{
    /**
     * @var Document
     */
    private Document $parent;

    /**
     * ProxyTypeSystemDocument constructor.
     *
     * @param Document $parent
     */
    public function __construct(Document $parent)
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritDoc}
     */
    public function schemas(): array
    {
        return \array_merge(parent::schemas(), $this->parent->schemas());
    }

    /**
     * {@inheritDoc}
     */
    public function types(): array
    {
        return \array_merge(parent::types(), $this->parent->types());
    }

    /**
     * {@inheritDoc}
     */
    public function hasType(string $name): bool
    {
        return parent::hasType($name) ?: $this->parent->hasType($name);
    }

    /**
     * {@inheritDoc}
     */
    public function hasDirective(string $name): bool
    {
        return parent::hasDirective($name) ?: $this->parent->hasDirective($name);
    }

    /**
     * {@inheritDoc}
     */
    public function directives(): array
    {
        return \array_merge(parent::directives(), $this->parent->directives());
    }

    /**
     * {@inheritDoc}
     */
    public function executions(): array
    {
        return \array_merge(parent::executions(), $this->parent->executions());
    }
}
