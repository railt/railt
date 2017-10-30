<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;

/**
 * Trait Support
 */
trait Support
{
    /**
     * @var string
     */
    private static $syntaxList = '[%s]';

    /**
     * @var string
     */
    private static $syntaxNonNull = '%s!';

    /**
     * @param AllowsTypeIndication|Definition $type
     * @return string
     */
    protected function typeToString(Definition $type): string
    {
        if ($type instanceof TypeDefinition) {
            [$parent, $name] = [$type->getTypeName(), $type->getName()];

            if ($type instanceof AllowsTypeIndication) {
                $name = \sprintf('%s: %s', $type->getName(), $this->typeIndicatorToString($type));
            }

            return \sprintf('%s "%s"', $parent, $name);
        }

        return \class_basename($type);
    }

    /**
     * @param AllowsTypeIndication $type
     * @return string
     */
    protected function typeIndicatorToString(AllowsTypeIndication $type): string
    {
        $result = $type->getTypeDefinition()->getName();

        if ($type->isList()) {
            if ($type->isListOfNonNulls()) {
                $result = \sprintf(self::$syntaxNonNull, $result);
            }

            $result = \sprintf(self::$syntaxList, $result);
        }

        if ($type->isNonNull()) {
            $result = \sprintf(self::$syntaxNonNull, $result);
        }

        return $result;
    }
}
