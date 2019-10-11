<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Container\ContainerInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

/**
 * Interface HttpKernelInterface
 */
interface HttpKernelInterface
{
    /**
     * @param ContainerInterface $app
     * @param RequestInterface $request
     * @param HandlerInterface $action
     * @return ResponseInterface
     */
    public function handle(
        ContainerInterface $app,
        RequestInterface $request,
        HandlerInterface $action
    ): ResponseInterface;

    /**
     * @param ContainerInterface $app
     * @param InputInterface $input
     * @param HandlerInterface $action
     * @return OutputInterface
     */
    public function call(
        ContainerInterface $app,
        InputInterface $input,
        HandlerInterface $action
    ): OutputInterface;
}
