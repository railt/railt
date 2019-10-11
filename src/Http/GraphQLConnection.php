<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Http;

use Railt\Http\RequestInterface;
use Railt\Http\HttpKernelInterface;
use Railt\Container\ContainerInterface;
use Railt\TypeSystem\Document\DocumentInterface;
use Railt\Http\Pipeline\Handler\HandlerInterface;

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
     * @param HttpKernelInterface $kernel
     * @param DocumentInterface $document
     */
    public function __construct(ContainerInterface $app, HttpKernelInterface $kernel, DocumentInterface $document)
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
}
