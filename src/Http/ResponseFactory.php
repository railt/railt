<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Exception\Factory;
use Railt\Exception\Location\Location;
use Railt\Exception\Location\MutableLocationsProviderInterface;

/**
 * Class ResponseFactory
 */
class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @return ResponseInterface
     */
    public function empty(): ResponseInterface
    {
        $exception = Factory::public('GraphQL request must contain a valid query data, but it came empty');

        if ($exception instanceof MutableLocationsProviderInterface) {
            $exception->withLocation(new Location(0, 0));
        }

        return (new Response())->withException($exception);
    }
}
