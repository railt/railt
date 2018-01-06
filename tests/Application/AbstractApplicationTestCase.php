<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Application;

use Railt\Foundation\Application;
use Railt\Http\RequestInterface;
use Railt\Reflection\Filesystem\File;
use Railt\Reflection\Filesystem\ReadableInterface;
use Railt\Tests\GraphQL\AbstractCompilerTestCase;
use Railt\Tests\Http\Mocks\Request;

/**
 * Class AbstractApplicationTestCase
 */
abstract class AbstractApplicationTestCase extends AbstractCompilerTestCase
{
    /**
     * @param string $file
     * @return string
     */
    final public function resource(string $file): string
    {
        return __DIR__ . '/.resources/' . $this->resourcesPath . $file;
    }

    /**
     * @param string $body
     * @return ReadableInterface
     */
    protected function query(string $body): ReadableInterface
    {
        return File::fromSources(
            'schema { query: Query } ' . "\n" . $body
        );
    }

    /**
     * @param string $body
     * @return ReadableInterface
     */
    protected function mutation(string $body): ReadableInterface
    {
        return File::fromSources(
            'schema { query: Query, mutation: Mutation } type Query {} ' . "\n" . $body
        );
    }

    /**
     * @return \Traversable|Application[]
     */
    protected function getApplications(): \Traversable
    {
        foreach ($this->getCompilers() as $compiler) {
            yield new Application($compiler);
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
