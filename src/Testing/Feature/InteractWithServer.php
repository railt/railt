<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Feature;

use Railt\Io\File;
use Railt\Testing\TestServer;

/**
 * Trait InteractWithServer
 */
trait InteractWithServer
{
    use InteractWithEnvironment;

    /**
     * @return string
     */
    protected function serverClass(): string
    {
        return TestServer::class;
    }

    /**
     * @param string $schema
     * @param bool $debug
     * @return TestServer
     */
    protected function schema(string $schema, bool $debug = null): TestServer
    {
        $schema = \sprintf('schema { query: Query } %s', $schema);
        $class  = $this->serverClass();

        return new $class(File::fromSources($schema), $debug ?? $this->isDebug);
    }
}
