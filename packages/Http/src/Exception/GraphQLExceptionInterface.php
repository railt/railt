<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Http\Common\RenderableInterface;
use Railt\Http\Extension\ExtensionsProviderInterface;
use Ramsey\Collection\CollectionInterface;
use Railt\Http\Exception\Location\LocationsProviderInterface;

/**
 * Interface GraphQLExceptionInterface
 */
interface GraphQLExceptionInterface extends
    \Throwable,
    RenderableInterface,
    LocationsProviderInterface,
    ExtensionsProviderInterface
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
     * @return bool
     */
    public function isPublic(): bool;

    /**
     * @return GraphQLExceptionInterface|$this
     */
    public function publish(): self;

    /**
     * @return GraphQLExceptionInterface|$this
     */
    public function hide(): self;

    /**
     * @return CollectionInterface|string[]|int[]
     */
    public function getPath(): CollectionInterface;
}
