<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Adapters;

use Railgun\Routing\Router;
use Railgun\Support\Dispatcher;
use Railgun\Adapters\Webonyx\Adapter as Webonyx;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Factory
 * @package Railgun\Adapters
 */
class Factory
{
    /**
     * @var array
     */
    private $adapters = [
        Webonyx::class
    ];

    /**
     * @param DocumentTypeInterface $document
     * @param Dispatcher $events
     * @param Router $router
     * @return AdapterInterface
     * @throws \LogicException
     */
    public function create(DocumentTypeInterface $document, Dispatcher $events, Router $router): AdapterInterface
    {
        $adapter = $this->resolveAdapter();

        return new $adapter($document, $events, $router);
    }

    /**
     * @return string
     * @throws \LogicException
     */
    private function resolveAdapter(): string
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter::isSupported()) {
                return $adapter;
            }
        }

        throw new \LogicException('Can not find allowed query adapter');
    }
}
