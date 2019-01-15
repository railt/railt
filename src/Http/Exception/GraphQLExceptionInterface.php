<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Http\Exception\Extension\ExtensionInterface;

/**
 * Interface GraphQLExceptionInterface
 */
interface GraphQLExceptionInterface extends \JsonSerializable
{
    /**
     * @var string
     */
    public const JSON_PATH_KEY = 'path';

    /**
     * @var string
     */
    public const JSON_MESSAGE_KEY = 'message';

    /**
     * @var string
     */
    public const JSON_LOCATIONS_KEY = 'locations';

    /**
     * @var string
     */
    public const JSON_EXTENSIONS_KEY = 'extensions';

    /**
     * @return iterable|GraphQLExceptionLocationInterface[]
     */
    public function getLocations(): iterable;

    /**
     * @return iterable|string[]|int[]
     */
    public function getPath(): iterable;

    /**
     * @return iterable|ExtensionInterface[]
     */
    public function getExtensions(): iterable;

    /**
     * @return bool
     */
    public function isPublic(): bool;
}
