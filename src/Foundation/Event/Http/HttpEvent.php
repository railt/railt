<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Railt\Http\Identifiable;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class HttpEvent
 */
abstract class HttpEvent extends Event implements HttpEventInterface
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
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * HttpEvent constructor.
     *
     * @param Identifiable $connection
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     */
    public function __construct(Identifiable $connection, RequestInterface $request, ResponseInterface $response = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->connection = $connection;
    }

    /**
     * @param RequestInterface $request
     * @return HttpEvent|$this
     */
    public function withRequest(RequestInterface $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     * @return HttpEvent|$this
     */
    public function withResponse(ResponseInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'connection' => $this->getConnection()->getId(),
            'request'    => $this->getRequest()->getId(),
        ];
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
