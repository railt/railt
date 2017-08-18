<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Adapters;

use Railgun\Http\ResponderInterface;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Railgun\Routing\Router;
use Railgun\Support\Dispatcher;

/**
 * Interface AdapterInterface
 * @package Railgun\Adapters
 */
interface AdapterInterface extends ResponderInterface
{
    /**
     * @return bool
     */
    public static function isSupported(): bool;

    /**
     * AdapterInterface constructor.
     * @param DocumentTypeInterface $document
     * @param Dispatcher $events
     * @param Router $router
     */
    public function __construct(DocumentTypeInterface $document, Dispatcher $events, Router $router);
}
