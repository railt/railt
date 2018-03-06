<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

/**
 * Interface Event
 */
interface Event
{
    /**
     * Field resolver.
     */
    public const DISPATCHING = 'dispatching';

    /**
     * Call the controller action.
     */
    public const RESOLVING = 'resolving';

    /**
     * Response of a controller action.
     */
    public const RESOLVED = 'resolved';

    /**
     * Response of the field resolver.
     */
    public const DISPATCHED = 'dispatched';
}
