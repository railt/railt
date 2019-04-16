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
 * An exception that occurs when internal mechanisms
 * are damaged during the construction of an abstract syntax tree.
 */
class BuildingException extends BaseSchemaException
{
}
