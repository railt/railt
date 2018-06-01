<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extensions;

use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Interface Extension
 */
interface Extension
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param RequestInterface $request
     * @param \Closure $then
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, \Closure $then): ResponseInterface;
}
