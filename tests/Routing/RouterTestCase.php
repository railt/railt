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
use Railt\Tests\TestCase;

/**
 * Class RouterTestCase
 */
class RouterTestCase extends TestCase
{
    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testRouteInterface(): void
    {
        $this->assertInstanceOf(RouterInterface::class, $this->mock());
    }

    /**
     * @return RouterInterface
     */
    private function mock(): RouterInterface
    {
        return new Router(new Container());
    }
}
