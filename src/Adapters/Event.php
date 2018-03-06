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
     * Build the arguments.
     */
    public const BUILDING = 'building:';

    /**
     * Field resolver.
     */
    public const ROUTE_DISPATCHING = 'route:dispatching:';

    /**
     * Response of the field resolver.
     */
    public const ROUTE_DISPATCHED = 'route:dispatched:';

    /**
     * Call the controller action.
     */
    public const ACTION_RESOLVING = 'action:resolving:';

    /**
     * Response of a controller action.
     */
    public const ACTION_RESOLVED = 'action:resolved:';
}
