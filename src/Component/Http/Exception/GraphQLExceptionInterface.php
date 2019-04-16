<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Exception;

use Railt\Component\Http\Extension\ProvidesExtensions;

/**
 * Interface GraphQLExceptionInterface
 */
interface GraphQLExceptionInterface extends ProvidesExtensions, \Throwable, \JsonSerializable
{
    /**
     * @var string
     */
    public const FIELD_PATH = 'path';

    /**
     * @var string
     */
    public const FIELD_MESSAGE = 'message';

    /**
     * @var string
     */
    public const FIELD_LOCATIONS = 'locations';

    /**
     * @return iterable|GraphQLExceptionLocationInterface[]
     */
    public function getLocations(): iterable;

    /**
     * @return iterable|string[]|int[]
     */
    public function getPath(): iterable;

    /**
     * @return bool
     */
    public function isPublic(): bool;

    /**
     * @return GraphQLExceptionInterface
     */
    public function publish(): self;

    /**
     * @return GraphQLExceptionInterface
     */
    public function hide(): self;
}
