<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Container\Exception;

/**
 * Class ParameterResolutionException
 */
class ParameterResolutionException extends ContainerResolutionException
{
    /**
     * @param string $message
     * @param \ReflectionFunctionAbstract $fn
     * @return ParameterResolutionException
     */
    public static function fromReflectionFunction(string $message, \ReflectionFunctionAbstract $fn): self
    {
        $exception = new static($message);
        $exception->file = $fn->getFileName();
        $exception->line = $fn->getStartLine();

        return $exception;
    }
}
