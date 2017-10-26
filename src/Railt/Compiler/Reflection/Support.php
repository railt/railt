<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Processable\ProcessableDefinition;

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
        $parent = $type->getTypeName();
        $name = $type->getName();

        if ($type instanceof AllowsTypeIndication) {
            $name = \sprintf('%s: %s', $type->getName(), $this->typeIndicatorToString($type));
        }

        return \sprintf('%s "%s"', $parent, $name);
    }

    /**
     * @param Definition $type
     * @return bool
     */
    protected function isUniqueType(Definition $type): bool
    {
        return !($type instanceof ProcessableDefinition);
    }

    /**
     * @param AllowsTypeIndication $type
     * @return string
     */
    protected function typeIndicatorToString(AllowsTypeIndication $type): string
    {
        $result = $type->getType()->getName();

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
