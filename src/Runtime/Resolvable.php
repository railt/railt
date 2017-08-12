<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime;

/**
 * Interface Resolvable
 * @package Serafim\Railgun\Runtime
 */
interface Resolvable
{
    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function resolve(RequestInterface $request);
}
