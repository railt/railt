<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Routing;

use Railt\Container\Container;
use Railt\Routing\Contracts\RouterInterface;
use Railt\Routing\Router;
use Railt\Tests\AbstractTestCase;

/**
 * Class RouterTestCase
 */
class RouterTestCase extends AbstractTestCase
{
    /**
     * @return RouterInterface
     */
    private function mock(): RouterInterface
    {
        return new Router(new Container());
    }
}
