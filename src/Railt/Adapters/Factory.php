<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Adapters\Webonyx\Adapter;
use Railt\Events\Dispatcher;
use Railt\Reflection\Contracts\DocumentInterface as Document;
use Railt\Routing\Router;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var array|AdapterInterface[]
     */
    private static $adapters = [
        Adapter::class,
    ];

    /**
     * @param string $class
     */
    public static function register(string $class): void
    {
        self::$adapters[] = $class;
    }

    /**
     * @param Document $document
     * @param Dispatcher $events
     * @param Router $router
     * @return AdapterInterface
     */
    public static function create(Document $document, Dispatcher $events, Router $router): AdapterInterface
    {
        foreach (self::$adapters as $class) {
            if (\class_exists($class) && $class::isSupported()) {
                return new $class($document, $events, $router);
            }
        }

        throw new \LogicException('Can not find available any GraphQL adapter.');
    }
}
