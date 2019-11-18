<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\Pipeline;

use Railt\Container\ContainerInterface;

/**
 * Interface PipelineInterface
 */
interface PipelineInterface
{
    /**
     * @param MiddlewareInterface|string ...$middleware
     * @return PipelineInterface|$this
     */
    public function through(...$middleware): self;

    /**
     * @param ContainerInterface $app
     * @return PipelineInterface|$this
     */
    public function using(ContainerInterface $app): self;
}
