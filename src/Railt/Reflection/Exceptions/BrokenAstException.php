<?php
/**
 * This file is part of Reflection package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Exceptions;

/**
 * Class BrokenAstException
 */
class BrokenAstException extends \LogicException
{
    use ExceptionHelper;
}
