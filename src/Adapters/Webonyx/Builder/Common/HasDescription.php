<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx\Builder\Common;

use Illuminate\Support\Str;
use Railt\Reflection\Abstraction\DefinitionInterface;
use Railt\Reflection\Abstraction\FieldInterface;
use Railt\Reflection\Abstraction\Type\TypeInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Trait HasDescription
 * @package Railt\Adapters\Webonyx\Builder\Common
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
            $this->formatName($target->getRelationDefinition()->getTypeName());
    }

    /**
     * @param NamedDefinitionInterface $definition
     * @return string
     */
    private function getNamedDefinitionDescription(NamedDefinitionInterface $definition): string
    {
        $result = '"' . $definition->getName() . '" ' . $this->formatName($definition->getTypeName());

        if ($definition instanceof FieldInterface) {
            $result .= ' of ' . $this->getTypeDescription($definition->getType());
        }

        return $result;
    }

    /**
     * @param DefinitionInterface $definition
     * @return string
     */
    private function getDefinitionDescription(DefinitionInterface $definition): string
    {
        return $definition->getTypeName() . ' of ' . $definition->getDocument()->getTypeName();
    }

    /**
     * @param string $name
     * @return string
     */
    private function formatName(string $name): string
    {
        return Str::snake($name, ' ');
    }
}
