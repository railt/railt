<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Exceptions;

/**
 * The exception that occurs when a known token is in
 * an undefined place and the result does not correspond
 * to any of the admissible structures.
 */
class UnexpectedTokenException extends UnrecognizedTokenException
{
}
