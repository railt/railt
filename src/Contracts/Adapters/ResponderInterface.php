<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Adapters;

use Serafim\Railgun\Requests\RequestInterface;

/**
 * Interface ResponderInterface
 * @package Serafim\Railgun\Contracts\Adapters
 */
interface ResponderInterface
{
    /**
     * @param RequestInterface $request
     * @param null $context
     * @return array
     */
    public function request(RequestInterface $request, $context = null): array;
}
