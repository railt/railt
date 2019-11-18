<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Foundation\Http;

use Railt\Foundation\HttpKernel;
use Railt\SDL\DocumentInterface;
use Railt\Container\ContainerInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Contracts\GraphQL\FactoryInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Http\Pipeline\Handler\RequestDecoratorHandler;

/**
 * Class Connection
 */
class GraphQLConnection extends Connection
{
    /**
     * @var DocumentInterface
     */
    private DocumentInterface $document;

    /**
     * Connection constructor.
     *
     * @param ContainerInterface $app
     * @param HttpKernel $kernel
     * @param DocumentInterface $document
     */
    public function __construct(ContainerInterface $app, HttpKernel $kernel, DocumentInterface $document)
    {
        $this->document = $document;

        parent::__construct($app, $kernel);
    }

    /**
     * @param RequestInterface $request
     * @param HandlerInterface $handler
     * @return ContainerInterface
     */
    protected function getContainer(RequestInterface $request, HandlerInterface $handler): ContainerInterface
    {
        $container = parent::getContainer($request, $handler);
        $container->instance(DocumentInterface::class, $this->document);

        return $container;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->sendTo($request, $this->getHandler($this->document));
    }

    /**
     * @param DocumentInterface $document
     * @return HandlerInterface
     */
    public function getHandler(DocumentInterface $document): HandlerInterface
    {
        if ($this->app->has(FactoryInterface::class)) {
            $factory = $this->app->make(FactoryInterface::class);

            return new RequestDecoratorHandler($factory->create($document));
        }

        return $this->getDefaultHandler();
    }
}
