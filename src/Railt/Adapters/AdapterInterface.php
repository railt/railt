<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Routing\Router;
use Railt\Http\ResponderInterface;
use Railt\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Interface AdapterInterface
 * @package Railt\Adapters
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
