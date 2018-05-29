<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing;

use Railt\Foundation\Application;
use Railt\Io\Readable;

/**
 * Class TestApplicationRequest
 */
class TestApplicationRequest extends TestRequest
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Readable
     */
    private $schema;

    /**
     * TestApplicationRequest constructor.
     * @param Readable $schema
     * @param Application $app
     */
    public function __construct(Readable $schema, Application $app)
    {
        $this->app    = $app;
        $this->schema = $schema;

        parent::__construct();
    }

    /**
     * @return TestResponse
     */
    public function send(): TestResponse
    {
        $response = $this->app->request($this->schema, $this->request);

        return new TestResponse($response);
    }
}
