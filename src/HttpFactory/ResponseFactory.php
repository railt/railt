<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory;

use Railt\Http\Response;
use Railt\Http\Exception\Factory;
use Railt\Http\ResponseInterface;
use Railt\Http\Exception\Location\MutableLocationsProviderInterface;

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
            $exception->withLocation(0, 0);
        }

        return (new Response())->withException($exception);
    }
}
