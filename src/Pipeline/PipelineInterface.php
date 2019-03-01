<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Pipeline;

/**
 * Interface PipelineInterface
 */
interface PipelineInterface
{
    /**
     * @param \Closure|null $then
     * @return PipelineInterface
     */
    public function then(?\Closure $then): PipelineInterface;

    /**
     * @param string|\Closure $middleware
     * @return PipelineInterface
     */
    public function through($middleware): PipelineInterface;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function send($value);
}
