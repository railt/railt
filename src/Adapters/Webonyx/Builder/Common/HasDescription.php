<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builder\Common;

use Illuminate\Support\Str;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Trait HasDescription
 * @package Serafim\Railgun\Adapters\Webonyx\Builder\Common
 */
trait HasDescription
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        $target = $this->getTarget();

        if ($target instanceof TypeInterface) {
            return $this->getTypeDescription($target);
        }

        if ($target instanceof NamedDefinitionInterface) {
            return $this->getNamedDefinitionDescription($target);
        }

        return $this->getDefinitionDescription($target);
    }

    /**
     * @param TypeInterface $target
     * @return string
     */
    private function getTypeDescription(TypeInterface $target): string
    {
        return $target->getRelationName() . ($target->isList() ? 's' : '') . ' ' .
            Str::lower($target->getRelationDefinition()->getTypeName());
    }

    /**
     * @param NamedDefinitionInterface $definition
     * @return string
     */
    private function getNamedDefinitionDescription(NamedDefinitionInterface $definition): string
    {
        return $definition->getName() . ' ' . Str::lower($definition->getName());
    }

    /**
     * @param DefinitionInterface $definition
     * @return string
     */
    private function getDefinitionDescription(DefinitionInterface $definition): string
    {
        return $definition->getTypeName() . ' of ' .
            $definition->getDocument()->getTypeName();
    }
}
