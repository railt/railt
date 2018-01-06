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
 * An error occurred if the source code semantics are not correct.
 *  - Type conversion errors.
 *  - Type inheritance errors.
 *  - Lack of required data.
 *  - Duplicating of definitions
 *  - etc.
 */
class TypeConflictException extends TypeException
{
}
