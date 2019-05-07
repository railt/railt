<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Connection;

use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\SDL\Contracts\Definitions\SchemaDefinition;
use Railt\Foundation\ConnectionInterface;

/**
 * Interface ExecutorInterface
 */
interface ExecutorInterface
{
    /**
     * @param ConnectionInterface $connection
     * @param RequestInterface $request
     * @param SchemaDefinition $schema
     * @return ResponseInterface
     */
    public function execute(ConnectionInterface $connection, RequestInterface $request, SchemaDefinition $schema): ResponseInterface;
}
