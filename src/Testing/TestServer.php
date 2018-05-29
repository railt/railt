<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing;

use Railt\Container\ContainerInterface;
use Railt\Foundation\Application;
use Railt\Http\Query;
use Railt\Http\QueryInterface;
use Railt\Http\Request;
use Railt\Http\RequestInterface;
use Railt\Io\Readable;

/**
 * Class Server
 */
class TestServer
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var Readable
     */
    private $schema;

    /**
     * @var Application
     */
    private $app;

    /**
     * Server constructor.
     * @param Readable $schema
     * @param bool $debug
     */
    public function __construct(Readable $schema, bool $debug = true)
    {
        $this->debug   = $debug;
        $this->schema  = $schema;
        $this->request = new Request();
    }

    /**
     * @param string $query
     * @param array $variables
     * @param string|null $operationName
     * @return TestServer
     */
    public function query(string $query, array $variables = [], string $operationName = null): self
    {
        return $this->addQuery(new Query($query, $variables, $operationName));
    }

    /**
     * @param QueryInterface $query
     * @return TestServer
     */
    public function addQuery(QueryInterface $query): self
    {
        $this->request->addQuery($query);

        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return TestServer
     */
    public function through(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param Application $app
     * @return $this
     */
    public function setApplication(Application $app): self
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @return TestResponse
     * @throws \InvalidArgumentException
     */
    public function send(): TestResponse
    {
        $app = $this->app ? $this->app : new Application($this->container, $this->debug);

        $response = $app->request($this->schema, $this->request);

        return new TestResponse($response);
    }
}
