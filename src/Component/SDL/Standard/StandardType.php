<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Standard;

/**
 * An interface that standardizes the constructor
 * of all types of the standard library.
 */
interface StandardType
{
    /**
     * RFC Implementation Description.
     */
    public const RFC_IMPL_DESCRIPTION = 'At the moment the type is not supported by the 
        GraphQL standard, its implementation is not allowed in the future.';


    public const CONSTANT_IDENTIFIER = '00000000-0000-0000-0000-000000000000';
}
