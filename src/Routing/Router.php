<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Routing;

/**
 * Class Router
 * @package Railgun\Routing
 */
class Router
{
    /**
     * @var \Closure
     */
    private $resolver;

    /**
     * Router constructor.
     * @param \Closure $resolver
     */
    public function __construct(\Closure $resolver)
    {
        $this->resolver = $resolver;
    }
}
