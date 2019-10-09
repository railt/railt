<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Pipeline\Handler;

use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Http\Pipeline\RequestHandlerInterface;

/**
 * Class RequestEmptyHandler
 */
final class RequestEmptyHandler implements RequestHandlerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE =
        '%s cannot handle request; no request handler ' .
        'available to process the request';

    /**
     * @var string
     */
    private string $class;

    /**
     * EmptyPipelineHandler constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        throw $this->error();
    }

    /**
     * @return \Throwable
     */
    private function error(): \Throwable
    {
        return new \LogicException(\sprintf(self::ERROR_MESSAGE, $this->class));
    }
}
