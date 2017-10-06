<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Routing;

use Railt\Routing\Router;
use Railt\Tests\AbstractTestCase;
use Railt\Routing\Contracts\RouterInterface;

/**
 * Class RouterTestCase
 * @package Railt\Tests\Routing
 */
class RouterTestCase extends AbstractTestCase
{
    /**
     * @return RouterInterface
     */
    private function mock(): RouterInterface
    {
        return new Router();
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testRouterStrictMatches(): void
    {
        $router = $this->mock();
        $router->any('some', 'Action');

        $this->assertTrue($router->has('some'));
        $this->assertFalse($router->has('before.some'));
        $this->assertFalse($router->has('some.after'));
        $this->assertFalse($router->has('ssome'));
    }

    /**
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testRouterResolveMatched(): void
    {
        $router = $this->mock();
        $router->any('some', 'Action');
        $router->query('some', 'Some');

        $this->assertCount(2, $router->find('some'));
        $this->assertCount(0, $router->find('before.some'));
        $this->assertCount(0, $router->find('some.after'));
        $this->assertCount(0, $router->find('somea'));
        $this->assertFalse($router->has('some.after'));
    }
}
