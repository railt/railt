<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime\Webonyx\Builder\Common;

use Illuminate\Support\Str;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;

/**
 * Trait HasDescription
 * @package Serafim\Railgun\Runtime\Webonyx\Builder\Common
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
