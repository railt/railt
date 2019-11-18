<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware\Debug;

use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\Http\GraphQLErrorInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;

/**
 * Class ErrorUnwrapperMiddleware
 */
class ErrorUnwrapperMiddleware extends DebuggingMiddleware
{
    /**
     * @param RequestInterface $request
     * @param HandlerInterface $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, HandlerInterface $next): ResponseInterface
    {
        $response = $next->handle($request);

        if ($this->isDebug()) {
            foreach ($response->getErrors() as $error) {
                $error->publish();
                $error->withExtension('class', \get_class($error));
                $error->withExtension('code', $error->getCode());
                $error->withExtension('trace', $this->trace($error));
            }
        }

        return $response;
    }

    /**
     * @param GraphQLErrorInterface $error
     * @return array|string[]
     */
    protected function trace(GraphQLErrorInterface $error): array
    {
        $lines = \explode("\n", $error->getTraceAsString());

        $lines = \array_map(
            fn (string $line) =>
            \preg_replace('/^#\d+\h+/ium', '', $line),
            $lines
        );

        $lines = \array_map(
            fn (string $line) =>
            \preg_replace('/(.+?)\((\d+)\):\h.+/ium', '$1:$2', $line),
            $lines
        );

        return $lines;
    }
}
