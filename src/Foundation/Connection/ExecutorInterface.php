<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Connection;

use Railt\Foundation\ConnectionInterface;
use Railt\Component\Http\RequestInterface;
use Railt\Component\Http\ResponseInterface;
use Railt\Component\SDL\Contracts\Definitions\SchemaDefinition;

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
