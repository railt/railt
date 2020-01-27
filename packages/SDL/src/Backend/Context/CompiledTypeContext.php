<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Context;

use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;

/**
 * Class CompiledTypeContext
 */
class CompiledTypeContext implements DefinitionContextInterface
{
    /**
     * @var NamedTypeInterface
     */
    private NamedTypeInterface $type;

    /**
     * CompiledTypeContext constructor.
     *
     * @param NamedTypeInterface $type
     */
    public function __construct(NamedTypeInterface $type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->type->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(array $variables = []): DefinitionInterface
    {
        return $this->type;
    }
}
