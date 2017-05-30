<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Http;

/**
 * Interface ResponderInterface
 * @package Serafim\Railgun\Http
 */
interface ResponderInterface
{
    /**
     * @param RequestInterface $request
     * @return array
     */
    public function request(RequestInterface $request): array;
}
