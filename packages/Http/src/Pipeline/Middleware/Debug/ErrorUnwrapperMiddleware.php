<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Middleware\Debug;

use Railt\Http\Pipeline\Handler\HandlerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

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
            foreach ($response->getExceptions() as $exception) {
                $exception->publish();
            }
        }

        return $response;
    }
}
