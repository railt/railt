<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Exceptions;

/**
 * Class IndeterminateBehaviorException
 *
 * This exception serves to identify those parts that are
 * yet to be finalized and must never throws in future.
 *
 * @package Railt\Exceptions
 */
class IndeterminateBehaviorException extends RuntimeException
{
    /**
     * @param string $method
     * @throws IndeterminateBehaviorException
     */
    public static function notImplemented(string $method): void
    {
        throw static::new('%s() not implemented yet', $method);
    }

}
