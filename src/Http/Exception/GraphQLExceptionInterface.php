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
