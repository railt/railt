<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface AdapterInterface
 */
interface AdapterInterface
{
    /**
     * AdapterInterface constructor.
     * @param SchemaDefinition $schema
     */
    public function __construct(SchemaDefinition $schema);

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(RequestInterface $request): ResponseInterface;

    /**
     * @param TypeDefinition $definition
     * @return mixed
     */
    public function get(TypeDefinition $definition);
}
