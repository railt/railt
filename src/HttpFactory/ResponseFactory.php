<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory;

use Railt\Http\Exception\Factory;
use Railt\Http\Exception\Location\Location;
use Railt\Http\Exception\Location\MutableLocationsProviderInterface;
use Railt\Http\Response;
use Railt\Http\ResponseInterface;

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
