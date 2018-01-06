<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Exceptions;

/**
 * An exception that occurs when the source code contains
 * an unknown and invalid character.
 */
class UnrecognizedTokenException extends BaseSchemaException
{
}
