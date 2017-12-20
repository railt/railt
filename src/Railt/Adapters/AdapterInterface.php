<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Container\ContainerInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;

/**
 * Interface AdapterInterface
 */
interface AdapterInterface
{
    /**
     * AdapterInterface constructor.
     * @param ContainerInterface $container
     * @param bool $debug
     */
    public function __construct(ContainerInterface $container, bool $debug = false);

    /**
     * @param SchemaDefinition $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(SchemaDefinition $schema, RequestInterface $request): ResponseInterface;
}
