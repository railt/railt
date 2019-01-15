<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation;

use Railt\Testing\TestCase as BaseTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @return array
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function eventsProvider(): array
    {
        $app = $this->app();

        return [
            'Symfony Event Dispatcher' => [$app, $app->getContainer()->get(EventDispatcherInterface::class)],
        ];
    }
}
