<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation\Responses;

use Railt\Http\Request;
use Railt\Http\ResponseInterface;
use Phplrt\Io\File;
use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Tests\Foundation\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ResponsesTestCase
 */
abstract class ResponsesTestCase extends TestCase
{
    /**
     * @param string $field
     * @param string $body
     * @param \Closure $then
     * @return \Railt\Http\ResponseInterface
     */
    protected function request(string $field, string $body, \Closure $then): ResponseInterface
    {
        $request = new Request('{ ' . $field . ' ' . $body . ' }');

        $connection = $this->connection(function (FieldResolve $event) use ($field, $then): void {
            if ($event->getPath() === $field) {
                $event->withResult($then($event));
            }
        });

        return $connection->request($request);
    }

    /**
     * @param \Closure|null $resolver
     * @return ConnectionInterface
     */
    protected function connection(\Closure $resolver = null): ConnectionInterface
    {
        $schema = File::fromSources($this->getSchema(), __FILE__);

        $app = $this->app();

        $events = $app->get(EventDispatcherInterface::class);

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
