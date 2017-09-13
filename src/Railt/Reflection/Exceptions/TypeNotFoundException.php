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
 * Class TypeNotFoundException
 * @package Railt\Reflection\Exceptions
 */
class TypeNotFoundException extends TypeException
{
    /**
     * Default missing type message
     */
    public const DEFAULT_MESSAGE = 'Type "%s" not found';

    /**
     * Message which throws when autoload did not find any allowed type
     */
    public const AUTOLOADING_ERROR = 'Type "%s" not found and could not be loaded.';
}
