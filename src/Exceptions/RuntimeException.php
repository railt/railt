<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Exceptions;

/**
 * Class RuntimeException
 * @package Railgun\Exceptions
 */
class RuntimeException extends \RuntimeException
{
    use RailgunException;
}