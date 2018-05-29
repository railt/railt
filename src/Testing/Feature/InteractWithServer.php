<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Feature;

use Railt\Foundation\Application;
use Railt\Io\File;
use Railt\Io\Readable;
use Railt\Testing\TestApplicationRequest;
use Railt\Testing\TestEmptyRequest;
use Railt\Testing\TestRequestInterface;

/**
 * Trait InteractWithServer
 */
trait InteractWithServer
{
    use InteractWithEnvironment;

    /**
     * @param Readable $schema
     * @param bool|null $debug
     * @return TestRequestInterface
     */
    protected function schema(Readable $schema, bool $debug = null): TestRequestInterface
    {
        return new TestEmptyRequest($schema, $debug ?? $this->isDebug);
    }

    /**
     * @param Readable $schema
     * @param Application $app
     * @return TestRequestInterface
     */
    protected function appSchema(Readable $schema, Application $app): TestRequestInterface
    {
        return new TestApplicationRequest($schema, $app);
    }

    /**
     * @param string $schema
     * @param bool|null $debug
     * @return TestRequestInterface
     */
    protected function basicQuerySchema(string $schema, bool $debug = null): TestRequestInterface
    {
        $schema = \sprintf('schema { query: Query } %s', $schema);

        return $this->schema(File::fromSources($schema), $debug ?? $this->isDebug);
    }

    /**
     * @param string $schema
     * @param bool|null $debug
     * @return TestRequestInterface
     */
    protected function basicMutationSchema(string $schema, bool $debug = null): TestRequestInterface
    {
        $schema = \sprintf('schema { query: Query, mutation: Mutation } type Query { empty: Any } %s', $schema);

        return $this->schema(File::fromSources($schema), $debug ?? $this->isDebug);
    }
}
