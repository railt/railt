<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Phplrt\Exception\MutableException\MutableFileInterface;
use Railt\Http\Exception\Path\MutablePathProviderInterface;
use Phplrt\Exception\MutableException\MutablePositionInterface;
use Railt\Http\Exception\Location\MutableLocationsProviderInterface;

/**
 * Interface MutableGraphQLExceptionInterface
 */
interface MutableGraphQLExceptionInterface extends
    GraphQLExceptionInterface,
    MutablePathProviderInterface,
    MutableLocationsProviderInterface,
    MutableFileInterface,
    MutablePositionInterface
{
    /**
     * @return MutableGraphQLExceptionInterface|$this
     */
    public function publish(): self;

    /**
     * @return MutableGraphQLExceptionInterface|$this
     */
    public function hide(): self;
}
