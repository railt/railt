<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Exceptions;

/**
 * Class UnrecognizedNodeException
 */
class UnrecognizedNodeException extends \LogicException
{
    use ExceptionHelper;

    /**
     * Default missing node exception message
     */
    public const DEFAULT_MESSAGE = 'Unrecognized or not implemented AST node %s: %s';
}
