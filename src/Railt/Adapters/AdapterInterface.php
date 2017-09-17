<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Routing\Router;
use Railt\Http\ResponderInterface;
use Railt\Events\DispatcherInterface;
use Railt\Container\ContainerInterface;
use Railt\Reflection\Contracts\DocumentInterface;

/**
 * Interface AdapterInterface
 */
interface AdapterInterface extends ResponderInterface
{
    /**
     * AdapterInterface constructor.
     * @param DocumentInterface $document
     * @param DispatcherInterface $events
     * @param Router $router
     */
    public function __construct(DocumentInterface $document, DispatcherInterface $events, Router $router);

    /**
     * @return DispatcherInterface
     */
    public function getEvents(): DispatcherInterface;

    /**
     * @return Router
     */
    public function getRouter(): Router;

    /**
     * @return TypeLoaderInterface
     */
    public function getLoader(): TypeLoaderInterface;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @return bool
     */
    public static function isSupported(): bool;
}
