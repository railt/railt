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
use Railt\Io\Readable;

/**
 * Class TestEmptyRequest
 */
class TestEmptyRequest extends TestRequest
{
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
     * Server constructor.
     * @param Readable $schema
     * @param bool $debug
     */
    public function __construct(Readable $schema, bool $debug = true)
    {
        $this->debug  = $debug;
        $this->schema = $schema;

        parent::__construct();
    }

    /**
     * @param ContainerInterface $container
     * @return TestEmptyRequest
     */
    public function through(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return TestResponse
     * @throws \InvalidArgumentException
     */
    public function send(): TestResponse
    {
        $app = new Application($this->container, $this->debug);

        $response = $app->request($this->schema, $this->request);

        return new TestResponse($response);
    }
}
