<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing;

use Psr\Container\ContainerInterface as PSRContainer;
use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Foundation\Application;
use Railt\Http\Request;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Io\File;
use Railt\Io\Readable;

/**
 * Class TestServer
 */
class TestServer
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @var Readable
     */
    private $schema;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Server constructor.
     * @param string $schema
     * @param bool $debug
     */
    public function __construct(string $schema, bool $debug = false)
    {
        $this->schema    = $this->createSchema($schema);
        $this->container = new Container();
        $this->debug     = $debug;
    }

    /**
     * @param PSRContainer $container
     * @return TestServer
     */
    public function through(PSRContainer $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param string $schema
     * @return Readable
     */
    private function createSchema(string $schema): Readable
    {
        return File::fromSources($schema);
    }

    /**
     * @return ContainerInterface
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param string $query
     * @param array $variables
     * @param string|null $operation
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function handleRequest(string $query, array $variables = [], string $operation = null): ResponseInterface
    {
        $app = new Application($this->container, $this->debug);

        return $app->request($this->schema, $this->createRequest($query, $variables, $operation));
    }

    /**
     * @param string $query
     * @param array $variables
     * @param string|null $operation
     * @return RequestInterface
     */
    private function createRequest(string $query, array $variables = [], ?string $operation): RequestInterface
    {
        return new class($query, $variables, $operation) extends Request {
            public function __construct(string $query, array $variables, ?string $operation)
            {
                $this->data = [
                    $this->getQueryArgument()     => $query,
                    $this->getVariablesArgument() => $variables,
                    $this->getOperationArgument() => $operation,
                ];
            }
        };
    }

    /**
     * @param string $query
     * @param array $variables
     * @return TestResponse
     * @throws \InvalidArgumentException
     */
    public function request(string $query, array $variables = []): TestResponse
    {
        return new TestResponse($this->handleRequest($query, $variables));
    }

    /**
     * @param string $query
     * @param array $variables
     * @return TestResponse
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function requestSucceeded(string $query, array $variables = []): TestResponse
    {
        return $this->request($query, $variables)->successful();
    }

    /**
     * @param string $query
     * @param array $variables
     * @return TestResponse
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function requestRaisesErrors(string $query, array $variables = []): TestResponse
    {
        return $this->request($query, $variables)->hasErrors();
    }
}
