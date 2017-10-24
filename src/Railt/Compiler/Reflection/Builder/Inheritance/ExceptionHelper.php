<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Inheritance;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Compiler\Exceptions\TypeRedefinitionException;

/**
 * Trait ExceptionHelper
 */
trait ExceptionHelper
{
    /**
     * @var bool
     */
    protected $throws = true;

    /**
     * @var string
     */
    private static $syntaxList = '[%s]';

    /**
     * @var string
     */
    private static $syntaxNonNull = '%s!';

    /**
     * @param string $message
     * @param array ...$args
     * @return bool
     * @throws TypeConflictException
     */
    protected function throw(string $message, ...$args): bool
    {
        if ($this->throws) {
            throw new TypeRedefinitionException(\sprintf($message, ...$args));
        }

        return false;
    }

    /**
     * @param Definition $type
     * @return string
     */
    protected function typeToString(Definition $type): string
    {
        return \sprintf('%s(%s)', $type->getTypeName(), $type->getName());
    }

    /**
     * @param AllowsTypeIndication $type
     * @return string
     */
    protected function relationToString(AllowsTypeIndication $type): string
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
