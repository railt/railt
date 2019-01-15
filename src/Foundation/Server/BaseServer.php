<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Server;

use Railt\Foundation\ApplicationInterface;
use Railt\Foundation\ConnectionInterface;

/**
 * Class Server
 */
abstract class BaseServer implements ServerInterface
{
    /**
     * @var \Closure|null
     */
    private $requestHandler;

    /**
     * @var ApplicationInterface
     */
    protected $app;

    /**
     * Server constructor.
     * @param ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
     * @param \Closure $then
     */
    public function onRequest(\Closure $then): void
    {
        $this->requestHandler = $then;
    }

    /**
     * @return ConnectionInterface
     * @throws \RuntimeException
     */
    protected function connect(): ConnectionInterface
    {
        if ($this->requestHandler === null) {
            $error = 'On request event handler must be defined';
            throw new \RuntimeException($error);
        }

        return ($this->requestHandler)($this->app);
    }
}
