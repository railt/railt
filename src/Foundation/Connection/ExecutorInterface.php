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

/**
 * Interface ExecutorInterface
 */
interface ExecutorInterface
{
    /**
     * @param SchemaDefinition $schema
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function execute(SchemaDefinition $schema, RequestInterface $request): ResponseInterface;
}
