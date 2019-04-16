<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use Railt\Foundation\ConnectionInterface;
use Railt\Foundation\Event\Connection\ProvidesConnection;
use Railt\Foundation\Event\Http\ProvidesRequest;
use Railt\Component\Http\Identifiable;
use Railt\Component\Http\RequestInterface;

/**
 * Class Context
 */
class Context implements ProvidesConnection, ProvidesRequest
{
    /**
     * @var Identifiable
     */
    private $connection;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Context constructor.
     *
     * @param ConnectionInterface $connection
     * @param RequestInterface $request
     */
    public function __construct(ConnectionInterface $connection, RequestInterface $request)
    {
        $this->connection = $connection;
        $this->request = $request;
    }

    /**
     * @return Identifiable
     */
    public function getConnection(): Identifiable
    {
        return $this->connection;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
