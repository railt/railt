<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Exception;

use Railt\Exception\Location\LocationInterface;
use Ramsey\Collection\CollectionInterface;

/**
 * Interface GraphQLExceptionInterface
 */
interface GraphQLExceptionInterface extends \Throwable, \JsonSerializable
{
    /**
     * @var string
     */
    public const FIELD_MESSAGE = 'message';

    /**
     * @var string
     */
    public const FIELD_LOCATIONS = 'locations';

    /**
     * @var string
     */
    public const FIELD_PATH = 'path';

    /**
     * @return CollectionInterface|LocationInterface[]
     */
    public function getLocations(): CollectionInterface;

    /**
     * @return CollectionInterface|string[]|int[]
     */
    public function getPath(): CollectionInterface;
}
