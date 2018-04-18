<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Application;

use Railt\Container\Container;
use Railt\Foundation\Application;
use Railt\Http\RequestInterface;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Tests\AbstractTestCase;
use Railt\Tests\Http\Mocks\Request;
use Railt\Tests\SDL\Helpers\CompilerStubs;

/**
 * Class AbstractApplicationTestCase
 */
abstract class AbstractApplicationTestCase extends AbstractTestCase
{
    use CompilerStubs;

    /**
     * @param string $body
     * @return Readable
     */
    protected function query(string $body): Readable
    {
        return File::fromSources(
            'schema { query: Query } ' . "\n" . $body
        );
    }

    /**
     * @param string $body
     * @return Readable
     */
    protected function mutation(string $body): Readable
    {
        return File::fromSources(
            'schema { query: Query, mutation: Mutation } type Query {} ' . "\n" . $body
        );
    }

    /**
     * @return \Traversable|Application[]
     * @throws \Exception
     */
    protected function getApplications(): \Traversable
    {
        foreach ($this->getCompilers() as $compiler) {
            $container = new Container();
            $container->instance(CompilerInterface::class, $compiler);

            $app = new Application($container);

            yield $app;
        }
    }

    /**
     * @param string $query
     * @param array $variables
     * @param string|null $operation
     * @return Request
     */
    protected function request(string $query, array $variables = [], string $operation = null): RequestInterface
    {
        return new Request($query, $variables, $operation);
    }

    /**
     * @return array|Application[][]
     * @throws \Exception
     */
    public function provider(): array
    {
        $result = [];

        foreach ($this->getApplications() as $application) {
            $result[] = [$application];
        }

        return $result;
    }
}
