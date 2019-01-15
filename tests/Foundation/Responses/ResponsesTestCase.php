<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation\Responses;

use Railt\Foundation\ConnectionInterface;
use Railt\Io\File;
use Railt\Tests\Foundation\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ResponsesTestCase
 */
abstract class ResponsesTestCase extends TestCase
{
    /**
     * @param \Closure|null $resolver
     * @return ConnectionInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    protected function connection(\Closure $resolver = null): ConnectionInterface
    {
        $schema = File::fromSources($this->getSchema(), __FILE__);

        $app = $this->app();

        $events = $app->getContainer()->get(EventDispatcherInterface::class);

        if ($resolver) {
            $resolver($events);
        }

        return $app->connect($schema);
    }

    /**
     * @return string
     */
    abstract protected function getSchema(): string;
}
