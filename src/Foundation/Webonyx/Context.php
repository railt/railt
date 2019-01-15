<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use Railt\Foundation\Event\Connection\ProvidesConnection;
use Railt\Foundation\Event\Http\ProvidesRequest;
use Railt\Http\Identifiable;
use Railt\Http\RequestInterface;

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
     * @param Identifiable $connection
     * @param RequestInterface $request
     */
    public function __construct(Identifiable $connection, RequestInterface $request)
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
